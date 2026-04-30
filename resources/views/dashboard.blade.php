@extends('layouts.app')

@section('content')
<div class="container mt-4">

    <h3 class="mb-3">Business Dashboard</h3>

    <!-- FILTER -->
<div class="dropdown">

    <button class="btn btn-sm btn-outline-dark dropdown-toggle me-2"
            data-bs-toggle="dropdown">
        Report Filter
    </button>

    <div class="dropdown-menu p-3"
         style="min-width:180px; font-size:15px;">

        <form method="GET">

            <label class="form-label small">From</label>
            <input type="date" name="from"
                   class="form-control form-control-sm mb-2"
                   value="{{ request('from') }}">

            <label class="form-label small">To</label>
            <input type="date" name="to"
                   class="form-control form-control-sm mb-2"
                   value="{{ request('to') }}">

            <label class="form-label small">Category</label>
            <select name="category_id"
                    class="form-control form-control-sm mb-2">
                <option value="">All Categories</option>
                @foreach($categories as $cat)
                    <option value="{{ $cat->id }}"
                        {{ request('category_id') == $cat->id ? 'selected' : '' }}>
                        {{ $cat->name }}
                    </option>
                @endforeach
            </select>

            <a href="{{ url()->current() }}"
               class="d-block small text-secondary mb-2">
                Reset filter
            </a>

            <button class="btn btn-sm btn-primary w-100">
                Apply Filter
            </button>

        </form>

    </div>
</div><br>
   @if(request('from') || request('category_id'))
    <p class="text-muted">
        Showing results
        @if(request('from'))
            from {{ request('from') }} to {{ request('to') }}
        @endif

        @if(request('category_id'))
            for category: 
            {{ $categories->firstWhere('id', request('category_id'))->name ?? 'N/A' }}
        @endif
    </p>
@endif

    <!-- KPI CARDS -->
    <div class="row g-4">

        <!-- SALES -->
        <div class="col-md-3">
            <div class="card shadow">
                <div class="card-body">
                    <h6>Total Sales (in UGX)</h6>
                    <h5>{{ number_format($totalSales, 2) }}</h5>
                    <small>+{{ number_format($salesGrowth,1) }}%</small>
                </div>
            </div>
        </div>

        <!-- EXPENSES -->
        <div class="col-md-3">
            <div class="card shadow">
                <div class="card-body">
                    <h6>Total Expenses (in UGX)</h6>
                    <h5>{{ number_format($totalExpenses, 2) }}</h5>
                </div>
            </div>
        </div>

        <!-- NET PROFIT -->
        <div class="col-md-3">
            <div class="card shadow">
                <div class="card-body">
                    <h6>Net Profit (in UGX)</h6>
                    <h5>{{ number_format($profit, 2) }}</h5>
                </div>
            </div>
        </div>

        <!-- PROFIT MARGIN -->
        <div class="col-md-3">
            <div class="card shadow">
                <div class="card-body">
                    <h6>Profit Margin</h6>
                    <h5>{{ number_format($profitMargin,1) }}%</h5>
                </div>
            </div>
        </div>

        <!--NET PROFIT MARGIN -->
        <div class="col-md-3">
            <div class="card shadow">
                <div class="card-body">
                  <h6>Net Profit Margin</h6>
                  <h5 class="{{ $netProfitMargin < 0 ? 'text-danger' : 'text-success' }}">
                  {{ number_format($netProfitMargin, 1) }}%</h5>
             </div>
           </div>
         </div>

        <!-- SALES PROFIT -->
        <div class="col-md-3">
            <div class="card shadow">
                <div class="card-body">
                   <h6>Sales Profit (in UGX)</h6>
                   <h5>{{ number_format($totalSalesProfit, 2) }}</h5>
                </div>
               </div>
             </div>

        <!-- RENTAL INCOME -->
        <div class="col-md-3">
            <div class="card shadow">
                <div class="card-body">
                    <h6>Rental Income (in UGX)</h6>
                    <h5>{{ number_format($totalRentalIncome, 2) }}</h5>
                </div>
            </div>
        </div>

        <!-- INVENTORY VALUE -->
        <div class="col-md-3">
            <div class="card shadow">
                <div class="card-body">
                    <h6>Inventory Value (in UGX)</h6>
                    <h5>{{ number_format($inventoryValue, 2) }}</h5>
                </div>
            </div>
        </div>

        <!-- LOW STOCK -->
        <div class="col-md-3">
            <div class="card shadow">
                <div class="card-body">
                    <h6>Low Stock Items</h6>
                    <h5>{{ count($lowStock) }}</h5>
                </div>
            </div>
        </div>

        <!-- TOTAL PRODUCTS -->
        <div class="col-md-3">
            <div class="card shadow">
                <div class="card-body">
                    <h6>Total Products</h6>
                    <h5>{{ $totalProducts }}</h5>
                </div>
            </div>
        </div>
      
        <!-- TOTAL STOCK -->
        <div class="col-md-3">
            <div class="card shadow">
                <div class="card-body">
                    <h6>Total Stock</h6>
                    <h5>{{ number_format($totalStock) }}</h5>
                </div>
            </div>
        </div>


    </div>

    <!-- CHARTS -->
    <div class="row mt-4">

        <div class="col-md-6">
            <div class="card shadow">
                <div class="card-header">Sales Trend</div>
                <div class="card-body">
                    <canvas id="salesChart"></canvas>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card shadow">
                <div class="card-header">Expenses Trend</div>
                <div class="card-body">
                    <canvas id="expensesChart"></canvas>
                </div>
            </div>
        </div>

    </div>

    <!-- LOW STOCK TABLE -->
    <div class="card shadow mt-4">
        <div class="card-header">Low Stock Products</div>
        <div class="card-body">
          <div style="max-height: 250px; overflow-y: auto;">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Product</th>
                        <th>Stock</th>
                        <th>Minimum</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($lowStock as $product)
                        <tr>
                            <td>{{ $product->name }}</td>
                            <td class="text-danger fw-bold">{{ $product->stock }} {{ $product->unit->symbol ?? '' }}</td>
                            <td>{{ $product->min_stock }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="text-center">No low stock items</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>

<script>
const salesData = @json($salesChart);
const expensesData = @json($expensesChart);

new Chart(document.getElementById('salesChart'), {
    type: 'bar',
    data: {
        labels: Object.keys(salesData),
        datasets: [{
            label: 'Sales',
            data: Object.values(salesData)
        }]
    }
});

new Chart(document.getElementById('expensesChart'), {
    type: 'line',
    data: {
        labels: Object.keys(expensesData),
        datasets: [{
            label: 'Expenses',
            data: Object.values(expensesData)
        }]
    }
});
</script>

@endsection