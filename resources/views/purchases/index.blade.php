@extends('layouts.app')

@section('content')
@section('title', 'Purchases - BMSystem')

<h5 class="mb-3">Purchases</h5>

<div class="col-md-3">
<div class="alert alert-primary mb-3">
Total Purchases: <strong>UGX {{ number_format($totalPurchases,2) }}</strong>
</div>
</div>


<div class="d-flex justify-content-between mb-3">
<div class="d-flex justify-content-between align-items-center mb-3">
<div class="d-flex gap-2">

<a href="/purchases/create" class="btn btn-sm custom-pill-btn">
New
</a>

<button onclick="editPurchase()" class="btn btn-sm custom-pill-btn">
Edit
</button>

<button onclick="deletePurchase()" class="btn btn-sm custom-pill-btn">
Delete
</button>

<a href="{{ route('purchases.export') }}" 
class="btn btn-sm custom-pill-btn">
Export
</a>

<div class="dropdown">

<button class="btn btn-sm custom-pill-btn dropdown-toggle" data-bs-toggle="dropdown">
Report
</button>

<div class="dropdown-menu p-3" style="min-width:250px; z-index:1050;">

<form method="GET" action="/purchases">

    <label class="form-label">From</label>
    <input type="date" name="from" class="form-control mb-2" value="{{ request('from') }}">

    <label class="form-label">To</label>
    <input type="date" name="to" class="form-control mb-3" value="{{ request('to') }}">

     <!-- Reset Icon -->
    @if(request('from'))
    <a href="/purchases" 
       class="position-absolute top-0 end-0 me-2 mt-2 text-secondary"
       title="Reset Filter">
        <i class="bi bi-arrow-clockwise" style="font-size:18px;"></i>
    </a>
    @endif

    <button class="btn btn-sm btn-primary w-100">
        Apply Filter
    </button>

</form>
</div>
</div>

</div>
</div>

<form method="GET" action="/purchases" id="searchForm">
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


<input type="hidden" id="selectedPurchaseId">
<div class="card shadow-sm">
<div class="card-body">
<div style="max-height:400px; overflow-y:auto;">

        <table class="table table-sm table-bordered table-striped table-hover">

            <thead class="table-secondary sticky-top">

  <tr>
     <th>ID</th>
     <th>Product</th>
     <th>Quantity</th>
     <th>Cost Price</th>
     <th>Total</th>
     <th>Date</th>
  </tr>
 </thead>
 
<tbody>
@foreach($purchases as $purchase)

<tr onclick="selectRow(this, {{ $purchase->id }})">

<td>{{ $purchase->id }}</td>

<td>{{ $purchase->product->name }}</td>

<td>{{ $purchase->quantity }}</td>

<td>UGX {{ number_format($purchase->cost_price) }}</td>

<td>UGX {{ number_format($purchase->total_cost) }}</td>

<td>{{ $purchase->date }}</td>

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

document.getElementById('selectedPurchaseId').value = id;

}
</script>

<script>

function editPurchase(){

let id = document.getElementById('selectedPurchaseId').value;

if(!id){
alert("Please select a Purchase first");
return;
}

window.location.href = "/purchases/" + id + "/edit";

}

function deletePurchase(){

let id = document.getElementById('selectedPurchaseId').value;

if(!id){
alert("Please select a Purchase first");
return;
}

if(confirm("Are you sure you want to delete this Purchase?")){
document.getElementById('deleteForm').action = "/purchases/" + id;
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