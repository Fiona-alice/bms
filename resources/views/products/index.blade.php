@extends('layouts.app')

@section('content')

@section('title', 'Products - BMSystem')
<h5 class="mb-3">Product Inventory</h5>
<div class="d-flex justify-content-between align-items-center mb-3">
    <div class="d-flex align-items-center gap-3">
<form method="GET" action="/products" id="filterForm">
    <div class="position-relative" style="width: 250px;">
        <!-- Category Dropdown -->
        <select name="category" onchange="submitFilter()" class="form-control custom-select">
            <option value="">All Categories</option>

            @foreach($categories as $category)
                <option value="{{ $category->id }}"
                    {{ request('category') == $category->id ? 'selected' : '' }}>
                    {{ $category->name }}
                </option>
            @endforeach
        </select>
   <span class="custom-arrow">
        <i class="bi bi-chevron-down"></i>
    </span>
</div>
</form>
 
  <div class="alert alert-success bg-white mb-0 py-1 px-3">
    Total Stock: {{ number_format($totalStock) }} units <br>
    Stock Value: UGX {{ number_format($totalValue, 2) }}
  </div>

</div>

<script>
function submitFilter(){
    document.getElementById('filterForm').submit();
}
</script>   

<form method="GET" action="/products" id="searchForm">
<div class="position-relative" style="width: 250px;">
<input type="text"
name="search"
id="searchInput"
class="form-control pe-5 ps-4"
placeholder="Search..."
value="{{ request('search') }}"
onkeyup="autoSearch()">

      <!--  <i class="bi bi-search position-absolute top-50 start-0 translate-middle-y ps-2"></i> -->

        <!-- Refresh Icon -->
        <i class="bi bi-arrow-clockwise position-absolute top-50 end-0 translate-middle-y pe-2"
              style="cursor:pointer;"
              onclick="clearSearch()"></i>
</div>
</form>

</div>

<div class="d-flex justify-content-between align-items-center mb-3">

<div class="d-flex gap-2">

<a href="/products/create" class="btn btn-sm custom-pill-btn">
New
</a>

<button onclick="editProduct()" class="btn btn-sm custom-pill-btn">
Edit
</button>

<button onclick="deleteProduct()" class="btn btn-sm custom-pill-btn">
Delete
</button>

<a href="{{ route('products.export') }}" 
class="btn btn-sm custom-pill-btn">
Export
</a>

</div>

</div>


<input type="hidden" id="selectedProductId">
<div class="card shadow-sm">

<div class="card-body">

<div style="max-height:400px; overflow-y:auto;">

        <table class="table table-sm table-bordered table-striped table-hover">

            <thead class="table-secondary sticky-top">
                <tr>
                    <th>ID</th>
                    <th>Product</th>
                    <th>Cost Price</th>
                    <th>Selling Price</th>
                    <th>Stock</th>
                    <th>Category</th>
                    <th>Status</th>
                </tr>
            </thead>

            <tbody>

                @foreach($products as $product)

                <tr onclick="selectRow(this, {{ $product->id }})">

                    <td>{{ $product->id }}</td>

                    <td>{{ $product->name }}</td>

                    <td>UGX {{ number_format($product->cost_price) }}</td>

                    <td class="fw-bold text-success">
                        UGX {{ number_format($product->selling_price) }}
                    </td>

                    <td>{{ $product->stock }}</td>
                    <td>{{ $product->category->name ?? 'N/A' }}</td>
                    <td>

                        @if($product->stock <= $product->min_stock)

                        <span class="badge bg-danger">Low Stock</span>

                        @else

                        <span class="badge bg-success">In Stock</span>

                        @endif

                    </td>
                </tr>

                @endforeach

            </tbody>

        </table>

<form id="deleteForm" method="POST" style="display:none;">
@csrf
@method('DELETE')
</form>

<script>

let selectedRow = null;

function selectRow(row, id){

if(selectedRow){
selectedRow.classList.remove('table-primary');
}

row.classList.add('table-primary');

selectedRow = row;

document.getElementById('selectedProductId').value = id;

}
</script>

<script>

function editProduct(){

let id = document.getElementById('selectedProductId').value;

if(!id){
alert("Please select a product first");
return;
}

window.location.href = "/products/" + id + "/edit";

}

function deleteProduct(){

let id = document.getElementById('selectedProductId').value;

if(!id){
alert("Please select a product first");
return;
}

if(confirm("Are you sure you want to delete this product?")){
document.getElementById('deleteForm').action = "/products/" + id;
document.getElementById('deleteForm').submit();
}

}

</script>

<script>
function autoSearch(){
    let input = document.getElementById('searchInput');

    // Optional: delay search (better UX)
    clearTimeout(window.searchTimer);
    window.searchTimer = setTimeout(() => {
        document.getElementById('searchForm').submit();
    }, 1000); // 1 second delay
}

function clearSearch(){
    let input = document.getElementById('searchInput');
    input.value = '';
    document.getElementById('searchForm').submit();
}
</script>

    </div>
  
</div>

@endsection