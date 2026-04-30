<?php

namespace App\Http\Controllers;

use App\Models\Client;
use Illuminate\Http\Request;

class ClientController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
       $query = Client::latest();

       if ($request->search) {
        $query->where('name', 'like', '%' . $request->search . '%');
    }

    $clients = $query->get();

        return view('clients.index', compact('clients'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
      return view('clients.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
       $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'nin' => 'nullable|string|max:100',
            'email' => 'nullable|email',
            'address' => 'nullable|string|max:255',
        ]);

        Client::create($request->all());

        return redirect()->route('clients.index')
            ->with('success', 'Client added successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(Client $client)
    {
        return view('clients.show', compact('client'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Client $client)
    {
      return view('clients.edit', compact('client'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Client $client)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'nin' => 'nullable|varchar|max:100',
            'email' => 'nullable|email',
            'address' => 'nullable|string|max:255',
        ]);

        $client->update($request->all());

        return redirect()->route('clients.index')
            ->with('success', 'Client updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Client $client)
    {
        $client->delete();

        return redirect()->route('clients.index')
            ->with('success', 'Client deleted successfully');
    }

public function export()
{
    $clients = \App\Models\Client::all();

    $filename = "clients_export_" . now()->format('Ymd_His') . ".csv";

    $headers = [
        "Content-Type" => "text/csv",
        "Content-Disposition" => "attachment; filename=$filename",
    ];

    $callback = function() use ($clients) {

        $file = fopen('php://output', 'w');

        // Header row
        fputcsv($file, [
            'ID',
            'Name',
            'Phone',
            'NIN No',
            'Email',
            'Address'
        ]);

        foreach ($clients as $client) {
            fputcsv($file, [
                $client->id,
                $client->name,
                $client->phone,
                $client->nin,
                $client->email,
                $client->address
            ]);
        }

        fclose($file);
    };

    return response()->stream($callback, 200, $headers);
}

}
