<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;
use App\Models\Unit;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
          $categories = Category::all();
         
          $query = Product::with('category', 'unit');

          if ($request->filled('category')) {
          $query->where('category_id', $request->category);
          }

          if ($request->search) {
          $query->where('name','like','%'.$request->search.'%');
          }

          $products = $query->get();
          $totalStock = $products->sum('stock');
           
          $totalValue = $products->sum(function($p){
          return $p->stock * $p->cost_price;
          });

          return view('products.index', compact('products','categories','totalStock','totalValue'));
          
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
           $categories = Category::all();
           $units = Unit::all();
           return view('products.create', compact('categories', 'units'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

     $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'unit_id' => 'nullable|exists:units,id',
            'cost_price' => 'required|numeric|min:0',
            'selling_price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'min_stock' => 'required|integer|min:0',
        ]);


    Product::create([
        'name' => $request->name,
        'category_id' => $request->category_id,
        'unit_id' => $request->unit_id,
        'cost_price' => $request->cost_price,
        'selling_price' => $request->selling_price,
        'stock' => $request->stock,
        'min_stock' => $request->min_stock
    ]);

    return redirect('/products');

    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Product $product)
    {
        $categories = Category::all();
        $units = Unit::all();
        return view('products.edit', compact('product','categories', 'units'));
    }
    

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Product $product)
    {
         $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'cost_price' => 'required|numeric|min:0',
            'selling_price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'min_stock' => 'required|integer|min:0',
        ]);

        $product->update($request->all());

        return redirect('/products')->with('success', 'Product updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
       $product->delete();
        return redirect('/products')->with('success', 'Product deleted successfully!');
    }

public function export()
{
    $products = \App\Models\Product::all();

    $filename = "products_export_" . now()->format('Ymd_His') . ".csv";

    $headers = [
        "Content-Type" => "text/csv",
        "Content-Disposition" => "attachment; filename=$filename",
    ];

    $callback = function() use ($products) {

        $file = fopen('php://output', 'w');

        // Header row
        fputcsv($file, [
            'ID',
            'Name',
            'Category',
            'Stock',
            'Cost Price',
            'Selling Price'
        ]);

        foreach ($products as $product) {
            fputcsv($file, [
                $product->id,
                $product->name,
                optional($product->category)->name,
                $product->stock,
                $product->cost_price,
                $product->selling_price
            ]);
        }

        fclose($file);
    };

    return response()->stream($callback, 200, $headers);
}

}
