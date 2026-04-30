<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;

class CategoryController extends Controller
{

public function index(Request $request)
{
    $query = Category::latest();

    if ($request->search) {
        $query->where('name', 'like', '%' . $request->search . '%');
    }

    $categories = $query->get();

    return view('categories.index', compact('categories'));
}

public function create()
{
    return view('categories.create');
}

public function store(Request $request)
{
    $request->validate([
        'name' => 'required'
    ]);

    Category::create([
        'name' => $request->name
    ]);

    return redirect('/categories')->with('success','Category created successfully');
}

public function edit($id)
{
    $category = Category::findOrFail($id);

    return view('categories.edit', compact('category'));
}

public function update(Request $request, $id)
{
    $category = Category::findOrFail($id);

    $category->update([
        'name' => $request->name
    ]);

    return redirect('/categories')->with('success','Category updated');
}

public function destroy($id)
{
    Category::destroy($id);

    return redirect('/categories')->with('success','Category deleted');
}

public function export()
{
    $categories = \App\Models\Category::all();

    $filename = "categories_export_" . now()->format('Ymd_His') . ".csv";

    $headers = [
        "Content-Type" => "text/csv",
        "Content-Disposition" => "attachment; filename=$filename",
    ];

    $callback = function() use ($categories) {

        $file = fopen('php://output', 'w');

        // Header row
        fputcsv($file, [
            'ID',
            'Name'
        ]);

        foreach ($categories as $category) {
            fputcsv($file, [
                $category->id,
                $category->name
            ]);
        }

        fclose($file);
    };

    return response()->stream($callback, 200, $headers);
}

}