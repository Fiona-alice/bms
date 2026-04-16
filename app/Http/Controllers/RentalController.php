<?php

namespace App\Http\Controllers;


use App\Models\Rental;
use App\Models\Product;
use App\Models\Client;
use Illuminate\Http\Request;

class RentalController extends Controller
{
  public function index(Request $request)
{
    $query = Rental::with('product')->latest();

     if ($request->filled('search')) {
    $query->where(function($q) use ($request) {
        $q->whereHas('product', function($q2) use ($request) {
            $q2->where('name', 'like', '%' . $request->search . '%');
        })
        ->orWhereHas('client', function($q2) use ($request) {
            $q2->where('name', 'like', '%' . $request->search . '%');
        });
    });
}

    $rentals = $query->get();
    $totalRentalIncome = $rentals->sum('rental_price');
    return view('rentals.index', compact('rentals','totalRentalIncome'));
}

public function create()
{
    $products = Product::all();
    $clients = Client::all();
    return view('rentals.create', compact('products', 'clients'));
}

public function store(Request $request)
{
    $request->validate([
        'product_id' => 'required',
        'client_id' => 'required|exists:clients,id',
        'quantity' => 'required|integer|min:1',
        'rental_price' => 'required|numeric',
        'date_out' => 'required|date',
        'expected_return' => 'required|date|after_or_equal:date_out'
    ]);

    $product = Product::findOrFail($request->product_id);

    if($product->stock < $request->quantity){
        return back()->with('error','Not enough stock available');
    }

    Rental::create($request->all());

    $product->stock -= $request->quantity;
    $product->save();

    return redirect('/rentals')->with('success','Item hired successfully');
}

public function edit($id)
{
    $rental = Rental::findOrFail($id);
    $products = Product::all();
    $clients = Client::all();

    return view('rentals.edit', compact('rental', 'products', 'clients'));
}

public function update(Request $request, $id)
{
    $rental = Rental::findOrFail($id);

    $request->validate([
        'product_id' => 'required|exists:products,id',
        'client_id' => 'required|exists:clients,id',
        'quantity' => 'required|integer|min:1',
        'rental_price' => 'required|numeric',
        'date_out' => 'required|date',
        'expected_return' => 'required|date|after_or_equal:date_out'
    ]);

    $rental->update($request->all());

    return redirect('/rentals')->with('success', 'Rental updated successfully');
}

public function destroy(Rental $rental)
    {
       $rental->delete();
        return redirect('/rentals')->with('success', 'Hire deleted successfully!');
    }


public function returnItem($id)
{
    $rental = Rental::findOrFail($id);

    $product = $rental->product;

    $product->stock += $rental->quantity;
    $product->save();

    $rental->status = 'returned';
    $rental->return_date = now();
    $rental->save();

    return redirect('/rentals')->with('success','Item returned');
}

public function export()
{
    $rentals = \App\Models\Rental::all();

    $filename = "rentals_export_" . now()->format('Ymd_His') . ".csv";

    $headers = [
        "Content-Type" => "text/csv",
        "Content-Disposition" => "attachment; filename=$filename",
    ];

    $callback = function() use ($rentals) {

        $file = fopen('php://output', 'w');

        // Header row
        fputcsv($file, [
            'ID',
            'Product',
            'Client',
            'Qty',
            'Rental Fee',
            'Date Out',
            'Expected Return',
            'Return Date',
            'Status'
        ]);

        foreach ($rentals as $rental) {
            fputcsv($file, [
                $rental->id,
                optional($rental->product)->name,
                optional($rental->client)->name,
                $rental->quantity,
                $rental->rental_price,
                $rental->date_out,
                $rental->expected_return,
                $rental->return_date,
                $rental->status
            ]);
        }

        fclose($file);
    };

    return response()->stream($callback, 200, $headers);
}

}
