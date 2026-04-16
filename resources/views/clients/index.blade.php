@extends('layouts.app')

@section('content')
@section('title', 'Clients - BMSystem')


<h5 class="mb-3">Clients</h5>


<div class="d-flex justify-content-between mb-3">
<div class="d-flex justify-content-between align-items-center mb-3">
<div class="d-flex gap-2">

      <a href="/clients/create" class="btn btn-sm custom-pill-btn">New</a>
      <button onclick="editClient()" class="btn btn-sm custom-pill-btn">Edit</button>
      <button onclick="deleteClient()" class="btn btn-sm custom-pill-btn">Delete</button>
      <a href="{{ route('clients.export') }}" class="btn btn-sm custom-pill-btn">Export</a>
</div>
</div>

<form method="GET" action="/clients" id="searchForm">
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

<input type="hidden" id="selectedClientId">
<div class="card shadow-sm">
<div class="card-body">

<div style="max-height:400px; overflow-y:auto;">
<table class="table table-sm table-bordered table-striped table-hover">

<thead class="table-secondary sticky-top">
<tr>
    <th>ID</th>
    <th>Name</th>
    <th>Phone</th>
    <th>NIN No</th>
    <th>Email</th>
    <th>Address</th>
</tr>
</thead>

<tbody>

@forelse($clients as $client)
<tr onclick="selectRow(this, {{ $client->id }})">
    <td>{{ $client->id }}</td>
    <td>{{ $client->name }}</td>
    <td>{{ $client->phone }}</td>
    <td>{{ $client->nin }}</td>
    <td>{{ $client->email }}</td>
    <td>{{ $client->address }}</td>
</tr>
@empty
<tr>
    <td colspan="5" class="text-center text-muted">No clients found</td>
</tr>
@endforelse

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

document.getElementById('selectedClientId').value = id;

}
</script>

<script>

function editClient(){

let id = document.getElementById('selectedClientId').value;

if(!id){
alert("Please select a Client first");
return;
}

window.location.href = "/clients/" + id + "/edit";

}

function deleteClient(){

let id = document.getElementById('selectedClientId').value;

if(!id){
alert("Please select a Client first");
return;
}

if(confirm("Are you sure you want to delete this Client?")){
document.getElementById('deleteForm').action = "/clients/" + id;
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