@extends('layouts.app')

@section('content')
@section('title', 'Rental/Hire - BMSystem')

    <h5 class="mb-3">Rental/Hires</h5>
    
    <div class="col-md-3">
    <div class="alert alert-primary">
    Rental Income: <strong>UGX {{ number_format($totalRentalIncome,2) }}</strong>
    </div>
    </div>

<div class="d-flex justify-content-between mb-3">
<div class="d-flex justify-content-between align-items-center mb-3">
<div class="d-flex gap-2">

<a href="/rentals/create" class="btn btn-sm custom-pill-btn">
New
</a>

<button onclick="editRental()" class="btn btn-sm custom-pill-btn">
Edit
</button>

<button onclick="deleteRental()" class="btn btn-sm custom-pill-btn">
Delete
</button>

<a href="{{ route('rentals.export') }}" 
class="btn btn-sm custom-pill-btn">
Export
</a>
</div>
</div>

<form method="GET" action="/rentals" id="searchForm">
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

<input type="hidden" id="selectedRentalId">
<div class="card shadow-sm">
<div class="card-body">
<div style="max-height:400px; overflow-y:auto;">

        <table class="table table-sm table-bordered table-striped table-hover">

            <thead class="table-secondary sticky-top">

<tr>
<th>ID</th>
<th>Product</th>
<th>Client</th>
<th>Qty</th>
<th>Rental Fee</th>
<th>Date Out</th>
<th>Expected Return</th>
<th>Return Date</th>
<th>Status</th>
<th>Action</th>
</tr>

</thead>

<tbody>

@foreach($rentals as $rental)

<tr onclick="selectRow(this, {{ $rental->id }})">

<td>{{ $rental->id }}</td>

<td>{{ optional($rental->product)->name }}</td>

<td>{{ optional($rental->client)->name }}</td>

<td>{{ $rental->quantity }}</td>

<td>UGX {{ number_format($rental->rental_price) }}</td>

<td>{{ $rental->date_out }}</td>

<td>
@if($rental->status == 'out' && \Carbon\Carbon::now()->gt($rental->expected_return))
<span class="badge bg-danger">Late</span>
{{ $rental->expected_return }}
@else
{{ $rental->expected_return }}
@endif
</td>

<td>{{ $rental->return_date }}</td>

<td>{{ $rental->status }}</td>

<td>

@if($rental->status == 'out')

<a href="/rentals/return/{{ $rental->id }}" class="btn btn-sm btn-success">
Return
</a>

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

document.getElementById('selectedRentalId').value = id;

}
</script>

<script>

function editRental(){

let id = document.getElementById('selectedRentalId').value;

if(!id){
alert("Please select a Hire first");
return;
}

window.location.href = "/rentals/" + id + "/edit";

}

function deleteRental(){

let id = document.getElementById('selectedRentalId').value;

if(!id){
alert("Please select a Hire first");
return;
}

if(confirm("Are you sure you want to delete this Hire?")){
document.getElementById('deleteForm').action = "/rentals/" + id;
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