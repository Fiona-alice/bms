<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\Sale;
use App\Models\Expense;
use App\Models\Product;
use App\Models\Rental;
use App\Models\Category;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
    
    $from = request('from');
    $to = request('to');

    $categoryId = request('category_id');
    $salesQuery = Sale::query();
    $expensesQuery = Expense::query();
    $rentalQuery = Rental::query();

    // ✅ Apply filter
    if ($from && $to) {
        $from = Carbon::parse($from)->startOfDay();
        $to = Carbon::parse($to)->endOfDay();

        $salesQuery->whereBetween('date', [$from, $to]);
        $expensesQuery->whereBetween('date', [$from, $to]);
        $rentalQuery->whereBetween('date_out', [$from, $to]);
    }

if ($categoryId) {

            // SALES
            $salesQuery->whereHas('product', function ($q) use ($categoryId) {
                $q->where('category_id', $categoryId);
            });

            // RENTALS (if linked to product)
            $rentalQuery->whereHas('product', function ($q) use ($categoryId) {
                $q->where('category_id', $categoryId);
            });

            // PRODUCTS (IMPORTANT for stock & inventory)
            $products = Product::where('category_id', $categoryId)->get();

        } else {
            $products = Product::all();
        }

    // ✅ KPIs (NOW FILTERED)
    $products = Product::all();
   
    $totalSales = (clone $salesQuery)->sum('total_price');
    $totalExpenses = (clone $expensesQuery)->sum('amount');
    $totalProducts = Product::count();
    $totalRentalIncome = (clone $rentalQuery)->sum('rental_price');
    $totalSalesProfit = (clone $salesQuery)->sum('profit');
    $totalStock = $products->sum('stock');

    $profit = $totalSalesProfit + $totalRentalIncome - $totalExpenses;

    $lowStock = Product::whereColumn('stock', '<=', 'min_stock')->get();
    
    $totalRevenue = $totalSales + $totalRentalIncome;

    $netProfitMargin = $totalRevenue > 0
    ? ($profit / $totalRevenue) * 100
    : 0;

    $profitMargin = $totalSales > 0 
        ? ($totalSalesProfit / $totalSales) * 100 
        : 0;

    $inventoryValue = Product::sum(DB::raw('stock * cost_price'));

    // ✅ SALES LAST MONTH (FIXED WITH YEAR)
    $salesLastMonth = Sale::whereYear('created_at', now()->year)
        ->whereMonth('created_at', now()->subMonth()->month)
        ->sum('total_price');

    $salesGrowth = $salesLastMonth > 0
        ? (($totalSales - $salesLastMonth) / $salesLastMonth) * 100
        : 0;

    // ✅ CHARTS (FILTERED + DAILY TREND)
    $salesChart = (clone $salesQuery)
        ->selectRaw('DATE_FORMAT(date, "%Y-%m") as label, SUM(total_price) as total')
        ->groupBy('label')
        ->pluck('total', 'label');

    $expensesChart = (clone $expensesQuery)
        ->selectRaw('DATE_FORMAT(date, "%Y-%m") as label, SUM(amount) as total')
        ->groupBy('label')
        ->pluck('total', 'label');

 $categories = Category::all();
    return view('dashboard', compact(
        'totalSales',
        'totalExpenses',
        'profit',
        'profitMargin',
        'totalProducts',
        'inventoryValue',
        'lowStock',
        'totalRentalIncome',
        'totalSalesProfit',
        'netProfitMargin',
        'totalStock',
        'salesChart',
        'expensesChart',
        'salesGrowth',
        'categories'
    ));

  }
}