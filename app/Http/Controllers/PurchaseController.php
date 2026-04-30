<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Purchase;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PurchaseController extends Controller
{
   
    public function index(Request $request)
    {
        $query = Purchase::with('product')->latest();

        if ($request->filled('search')) {
            $query->whereHas('product', function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%');
            });
        }

      // DATE FILTER 
       if ($request->filled('from') && $request->filled('to')) {

        $from = Carbon::parse($request->from)->startOfDay();
        $to   = Carbon::parse($request->to)->endOfDay();

        $query->whereBetween('date', [$from, $to]);
        }

        $purchases = $query->get();

        //  Always use stored total
        $totalPurchases = $purchases->sum('total_cost');

        return view('purchases.index', compact('purchases','totalPurchases'));
    }

    // ================= CREATE =================
    public function create()
    {
        $products = Product::all();
        return view('purchases.create', compact('products'));
    }

    // ================= STORE =================
public function store(Request $request)
{
    $request->validate([
        'product_id' => 'required|exists:products,id',
        'quantity'   => 'required|numeric|min:1',
        'total_cost' => 'required|numeric|min:0',
        'date'       => 'required|date',
        // extra costs — all optional
        'costs.*.cost_type' => 'nullable|string',
        'costs.*.amount'    => 'nullable|numeric|min:0',
    ]);

    DB::transaction(function () use ($request) {

        $product  = Product::findOrFail($request->product_id);
        $newQty   = $request->quantity;
        $baseCost = $request->total_cost;  // supplier invoice total

        // ── Sum all extra import costs ──────────────────────────────
        $extraCosts = collect($request->input('costs', []))
            ->filter(fn($row) => !empty($row['amount']) && $row['amount'] > 0);

        $extraTotal = $extraCosts->sum('amount');

        // ── Landed total & unit cost ────────────────────────────────
        $landedTotal    = $baseCost + $extraTotal;
        $unitCost       = $baseCost / $newQty;          // supplier unit cost
        $landedUnitCost = $landedTotal / $newQty;       // true cost per unit

        // ── Weighted average uses landed cost ──────────────────────
        $oldStock = $product->stock;
        $oldCost  = $product->cost_price;
        $newStock = $oldStock + $newQty;

        $averageCost = $newStock > 0
            ? (($oldStock * $oldCost) + $landedTotal) / $newStock
            : 0;

        // ── Save purchase ──────────────────────────────────────────
        $purchase = Purchase::create([
            'product_id'       => $product->id,
            'quantity'         => $newQty,
            'cost_price'       => $unitCost,
            'landed_unit_cost' => $landedUnitCost,
            'extra_costs_total'=> $extraTotal,
            'total_cost'       => $baseCost,      // keep original invoice total
            'date'             => $request->date,
        ]);

        // ── Save each cost line ────────────────────────────────────
        foreach ($extraCosts as $row) {
            $purchase->costs()->create([
                'cost_type'   => $row['cost_type'],
                'description' => $row['description'] ?? null,
                'amount'      => $row['amount'],
            ]);
        }

        // ── Update product ─────────────────────────────────────────
        $product->update([
            'stock'      => $newStock,
            'cost_price' => $averageCost,   // now reflects true landed cost
        ]);
    });

    return redirect('/purchases')->with('success', 'Purchase recorded successfully!');
}

    // ================= EDIT =================
    public function edit(Purchase $purchase)
    {
        $products = Product::all();
        return view('purchases.edit', compact('purchase', 'products'));
    }

   // ================= UPDATE =================
public function update(Request $request, Purchase $purchase)
{
    $request->validate([
        'product_id' => 'required|exists:products,id',
        'quantity'   => 'required|numeric|min:1',
        'total_cost' => 'required|numeric|min:0',
        'date'       => 'required|date',
        'costs.*.cost_type' => 'nullable|string',
        'costs.*.amount'    => 'nullable|numeric|min:0',
    ]);

    DB::transaction(function () use ($request, $purchase) {

        $product = Product::findOrFail($request->product_id);

        // ── Remove old purchase effect (use old landed total) ──────
        $oldLandedTotal = $purchase->total_cost + $purchase->extra_costs_total;
        $currentValue   = $product->stock * $product->cost_price;

        $stockAfterRemoval = $product->stock - $purchase->quantity;
        $valueAfterRemoval = $currentValue - $oldLandedTotal;

        // ── New values ─────────────────────────────────────────────
        $newQty     = $request->quantity;
        $baseCost   = $request->total_cost;

        $extraCosts = collect($request->input('costs', []))
            ->filter(fn($row) => !empty($row['amount']) && $row['amount'] > 0);

        $extraTotal     = $extraCosts->sum('amount');
        $landedTotal    = $baseCost + $extraTotal;
        $landedUnitCost = $landedTotal / $newQty;
        $unitCost       = $baseCost / $newQty;

        $finalStock = $stockAfterRemoval + $newQty;
        $finalValue = $valueAfterRemoval + $landedTotal;

        $newAverageCost = $finalStock > 0 ? $finalValue / $finalStock : 0;

        // ── Update purchase & costs ────────────────────────────────
        $purchase->update([
            'product_id'        => $product->id,
            'quantity'          => $newQty,
            'cost_price'        => $unitCost,
            'landed_unit_cost'  => $landedUnitCost,
            'extra_costs_total' => $extraTotal,
            'total_cost'        => $baseCost,
            'date'              => $request->date,
        ]);

        // Replace all cost lines
        $purchase->costs()->delete();
        foreach ($extraCosts as $row) {
            $purchase->costs()->create([
                'cost_type'   => $row['cost_type'],
                'description' => $row['description'] ?? null,
                'amount'      => $row['amount'],
            ]);
        }

        $product->update([
            'stock'      => $finalStock,
            'cost_price' => $newAverageCost,
        ]);
    });

    return redirect('/purchases')->with('success', 'Purchase updated successfully!');
}

   // ================= DELETE =================
// Only change: use landed total when reversing
public function destroy(Purchase $purchase)
{
    DB::transaction(function () use ($purchase) {

        $product     = $purchase->product;
        $landedTotal = $purchase->total_cost + $purchase->extra_costs_total;

        $currentValue = $product->stock * $product->cost_price;
        $newStock     = $product->stock - $purchase->quantity;
        $newValue     = $currentValue - $landedTotal;

        $newAverageCost = $newStock > 0 ? $newValue / $newStock : 0;

        $product->update([
            'stock'      => $newStock,
            'cost_price' => $newAverageCost,
        ]);

        $purchase->delete(); // costs cascade-delete automatically
    });

    return redirect('/purchases')->with('success', 'Purchase deleted successfully!');
}

    // ================= EXPORT =================
    public function export()
    {
        $purchases = Purchase::with('product')->get();

        $filename = "purchases_export_" . now()->format('Ymd_His') . ".csv";

        $headers = [
            "Content-Type" => "text/csv",
            "Content-Disposition" => "attachment; filename=$filename",
        ];

        $callback = function() use ($purchases) {

            $file = fopen('php://output', 'w');

            fputcsv($file, ['ID','Product','Quantity','Cost Price','Total','Date']);

            foreach ($purchases as $purchase) {
                fputcsv($file, [
                    $purchase->id,
                    optional($purchase->product)->name,
                    $purchase->quantity,
                    $purchase->cost_price,
                    $purchase->total_cost,
                    $purchase->date
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}