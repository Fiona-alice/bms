<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Expense;
use Illuminate\Http\Request;

class ExpenseController extends Controller
{
     public function index(Request $request)
    {
        $query = Expense::latest();
        
        if ($request->filled('search')) {
        $query->where('category', 'like', '%' . $request->search . '%');
        }

 // DATE FILTER 
       if ($request->filled('from') && $request->filled('to')) {

        $from = Carbon::parse($request->from)->startOfDay();
        $to   = Carbon::parse($request->to)->endOfDay();

        $query->whereBetween('date', [$from, $to]);
        }

        $expenses = $query->get();
        $totalExpenses = $expenses->sum('amount');

        return view('expenses.index', compact('expenses','totalExpenses'));
    }

    public function create()
    {
        return view('expenses.create');
    }

    public function store(Request $request)
    {
        Expense::create($request->all());

        return redirect()->route('expenses.index')
            ->with('success', 'Expense added successfully');
    }

   public function edit(Expense $expense)
{
    return view('expenses.edit', compact('expense'));
}

public function update(Request $request, Expense $expense)
{
    $request->validate([
        'category' => 'required|string|max:255',
        'amount' => 'required|numeric|min:0',
        'date' => 'required|date',
        'description' => 'nullable|string',
    ]);

    $expense->update($request->all());
    return redirect()->route('expenses.index')->with('success', 'Expense updated successfully!');
}

public function destroy(Expense $expense)
{
    $expense->delete();
    return redirect()->route('expenses.index')->with('success', 'Expense deleted successfully!');
}

public function export()
{
    $expenses = \App\Models\Expense::all();

    $filename = "expenses_export_" . now()->format('Ymd_His') . ".csv";

    $headers = [
        "Content-Type" => "text/csv",
        "Content-Disposition" => "attachment; filename=$filename",
    ];

    $callback = function() use ($expenses) {

        $file = fopen('php://output', 'w');

        // Header row
        fputcsv($file, [
            'ID',
            'Category',
            'Amount',
            'Date',
            'Description'
        ]);

        foreach ($expenses as $expense) {
            fputcsv($file, [
                $expense->id,
                $expense->category,
                $expense->amount,
                $expense->description
            ]);
        }

        fclose($file);
    };

    return response()->stream($callback, 200, $headers);
}


}
