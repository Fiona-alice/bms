<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Sale;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SaleController extends Controller
{
    
    public function index(Request $request)
    {

     $query = Sale::with('product');

    if ($request->filled('from') && $request->filled('to')) {

       $from = Carbon::parse($request->from)->startOfDay();
       $to = Carbon::parse($request->to)->endOfDay();

        $query->whereBetween('date', [$from, $to]);
    }
  
    if ($request->filled('search')) {
    $query->whereHas('product', function($q) use ($request) {
        $q->where('name', 'like', '%' . $request->search . '%');
    });
   }
     
    // Load all sales with product relation
       $sales = $query->latest()->get();

       $totalSales = $sales->sum('total_price');
       $totalProfit = $sales->sum('profit');

       return view('sales.index', compact('sales', 'totalSales', 'totalProfit'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
       $products = Product::all();
       return view('sales.create', compact('products'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
       // Validate input
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
            'selling_price' => 'required|numeric|min:0',
            'date' => 'required|date',
        ]);

        DB::transaction(function () use ($request) {

        $product = Product::findOrFail($request->product_id);

        $quantity = $request->quantity;

        $costPrice = $product->cost_price;
        $sellingPrice = $request->selling_price;

        //  CALCULATIONS
        $totalSales = $sellingPrice * $quantity;
        $totalCost  = $costPrice * $quantity;   // COGS
        $profit     = $totalSales - $totalCost;    

        // Create the sale record
        $sale = Sale::create([
            'product_id' => $product->id,
            'quantity' => $quantity,
            'unit_price' => $product->selling_price,
            'selling_price' => $sellingPrice,        
            'cost_price' => $costPrice,
            'total_price' => $totalSales,
            'total_cost'    => $totalCost, 
            'profit' => $profit,
            'date' => $request->date,
        ]);

    $product->stock -= $quantity;
    $product->save();
 });
    return redirect('/sales')->with('success', 'Sale recorded successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Sale $sale)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Sale $sale)
    {
        $products = Product::all();
        return view('sales.edit', compact('sale', 'products'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Sale $sale)
    {
         $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
            'selling_price' => 'required|numeric|min:0',
            'date' => 'required|date',
        ]);

        DB::transaction(function () use ($request, $sale) {

        $product = Product::findOrFail($request->product_id);

        // Restore old stock before updating
         $sale->product->stock += $sale->quantity;
         $sale->product->save();

         $quantity = $request->quantity;
         
         // NEW COST (AT UPDATE TIME)
        $costPrice = $product->cost_price;

        $sellingPrice = $request->selling_price;

        $totalSales = $sellingPrice * $quantity;
        $totalCost  = $costPrice * $quantity;
        $profit     = $totalSales - $totalCost;

        $sale->update([
            'product_id' => $product->id,
            'quantity' => $quantity,
            'unit_price' => $product->selling_price,
            'selling_price' => $sellingPrice,
            'cost_price' => $costPrice,
            'total_price' => $totalSales,
            'total_cost'    => $totalCost,
            'profit' => $profit,
            'date' => $request->date,
        ]);

        // Reduce new stock
        $product->stock -= $quantity;
        $product->save();
    });
        return redirect('/sales')->with('success', 'Sale updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */

public function destroy(Sale $sale)
{
    DB::transaction(function () use ($sale) {

        $product = $sale->product;

        if ($product) {
            $product->stock += $sale->quantity;
            $product->save();
        }

        $sale->delete();
    });

    return redirect('/sales')->with('success', 'Sale deleted successfully!');
}

public function export()
{
    $sales = Sale::with('product')->get();

    $filename = "sales_export_" . now()->format('Ymd_His') . ".csv";

    $headers = [
        "Content-Type" => "text/csv",
        "Content-Disposition" => "attachment; filename=$filename",
    ];

    $callback = function() use ($sales) {

        $file = fopen('php://output', 'w');

        // CSV Header row
        fputcsv($file, [
            'ID',
            'Product',
            'Quantity',
            'Unit Price',
            'Total',
            'Date',
            'Profit'
        ]);

        foreach ($sales as $sale) {

            fputcsv($file, [
                $sale->id,
                $sale->product->name,
                $sale->quantity,
                $sale->selling_price,
                $sale->total_price,
                $sale->date,
                $sale->profit
            ]);

        }

        fclose($file);
    };

    return response()->stream($callback, 200, $headers);
}

}

