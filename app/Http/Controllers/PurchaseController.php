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
        ]);

        DB::transaction(function () use ($request) {

            $product = Product::findOrFail($request->product_id);

            $oldStock = $product->stock;
            $oldCost  = $product->cost_price;

            $newQty    = $request->quantity;
            $totalCost = $request->total_cost;

            // ✅ precise unit cost
            $unitCost = $totalCost / $newQty;

            // ✅ new stock
            $newStock = $oldStock + $newQty;

            // ✅ values (money, not unit * qty)
            $totalOldValue = $oldStock * $oldCost;
            $totalNewValue = $totalCost;

            // ✅ weighted average
            $averageCost = $newStock > 0 
                ? ($totalOldValue + $totalNewValue) / $newStock 
                : 0;

            // ✅ save purchase
            Purchase::create([
                'product_id' => $product->id,
                'quantity'   => $newQty,
                'cost_price' => $unitCost,
                'total_cost' => $totalCost,
                'date'       => $request->date
            ]);

            // ✅ update product
            $product->update([
                'stock' => $newStock,
                'cost_price' => $averageCost
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
        ]);

        DB::transaction(function () use ($request, $purchase) {

            $product = Product::findOrFail($request->product_id);

            // 🔴 REMOVE OLD PURCHASE EFFECT
            $oldQty       = $purchase->quantity;
            $oldTotalCost = $purchase->total_cost;

            $currentStock = $product->stock;
            $currentCost  = $product->cost_price;

            $currentValue = $currentStock * $currentCost;

            $stockAfterRemoval = $currentStock - $oldQty;
            $valueAfterRemoval = $currentValue - $oldTotalCost;

            // 🟡 ADD NEW PURCHASE
            $newQty       = $request->quantity;
            $newTotalCost = $request->total_cost;

            $finalStock = $stockAfterRemoval + $newQty;
            $finalValue = $valueAfterRemoval + $newTotalCost;

            // 🟢 NEW AVERAGE
            $newAverageCost = $finalStock > 0 
                ? $finalValue / $finalStock 
                : 0;

            // 🔵 UPDATE PURCHASE
            $unitCost = $newTotalCost / $newQty;

            $purchase->update([
                'product_id' => $product->id,
                'quantity'   => $newQty,
                'cost_price' => $unitCost,
                'total_cost' => $newTotalCost,
                'date'       => $request->date,
            ]);

            // 🟣 UPDATE PRODUCT
            $product->update([
                'stock' => $finalStock,
                'cost_price' => $newAverageCost
            ]);
        });

        return redirect('/purchases')->with('success', 'Purchase updated successfully!');
    }

    // ================= DELETE =================
    public function destroy(Purchase $purchase)
    {
        DB::transaction(function () use ($purchase) {

            $product = $purchase->product;

            // 🔴 REMOVE PURCHASE EFFECT COMPLETELY
            $currentStock = $product->stock;
            $currentCost  = $product->cost_price;

            $currentValue = $currentStock * $currentCost;

            $newStock = $currentStock - $purchase->quantity;
            $newValue = $currentValue - $purchase->total_cost;

            $newAverageCost = $newStock > 0 
                ? $newValue / $newStock 
                : 0;

            // update product
            $product->update([
                'stock' => $newStock,
                'cost_price' => $newAverageCost
            ]);

            // delete purchase
            $purchase->delete();
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