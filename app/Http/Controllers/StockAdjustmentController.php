<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\StockAdjustment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StockAdjustmentController extends Controller
{
    // ================= INDEX =================
    public function index()
    {
        $adjustments = StockAdjustment::with('product')->latest()->get();

        $totalLoss = $adjustments
            ->where('quantity', '<', 0)
            ->sum('total_cost');

        return view('adjustments.index', compact('adjustments', 'totalLoss'));
    }

    // ================= CREATE =================
    public function create()
    {
        $products = Product::all();
        return view('adjustments.create', compact('products'));
    }

    // ================= STORE =================
    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity'   => 'required|integer|min:1',
            'type'       => 'required',
            'date'       => 'required|date',
        ]);

        DB::transaction(function () use ($request) {

            $product = Product::findOrFail($request->product_id);

            $qty = $request->quantity;
            $costPrice = $product->cost_price;

            //  Determine direction
            if (in_array($request->type, ['loss', 'damage', 'theft'])) {
                $finalQty = -$qty; // reduce stock
            } else {
                $finalQty = $qty; // increase stock
            }

            //  Prevent negative stock
            if ($product->stock + $finalQty < 0) {
                abort(400, 'Not enough stock for this adjustment');
            }

            $totalCost = round(abs($finalQty) * $costPrice, 2);

            //  Save adjustment
            StockAdjustment::create([
                'product_id' => $product->id,
                'quantity'   => $finalQty,
                'cost_price' => $costPrice,
                'total_cost' => $totalCost,
                'type'       => $request->type,
                'reason'     => $request->reason,
                'date'       => $request->date,
            ]);

            //  Update stock ONLY (do NOT touch cost price)
            $product->stock += $finalQty;
            $product->save();
        });

        return redirect('/adjustments')->with('success', 'Stock adjusted successfully!');
    }

    // ================= DELETE =================
    public function destroy(StockAdjustment $adjustment)
    {
        DB::transaction(function () use ($adjustment) {

            $product = $adjustment->product;

            // Reverse the adjustment
            $product->stock -= $adjustment->quantity;
            $product->save();

            $adjustment->delete();
        });

        return redirect('/adjustments')->with('success', 'Adjustment deleted successfully!');
    }
}