@extends('layouts.app')

@section('content')
@section('title', 'Users - BMSystem')

<h5>Users</h5>

<div class="d-flex justify-content-between mb-3">
<div class="d-flex justify-content-between align-items-center mb-3">
<div class="d-flex gap-2">

<a href="/users/create" class="btn btn-sm custom-pill-btn">
New</a>
<button onclick="editUser()" class="btn btn-sm custom-pill-btn">
Edit</button>
<button onclick="deleteUser()" class="btn btn-sm custom-pill-btn">
Delete</button>

</div>
</div>
</div>

<input type="hidden" id="selectedUserId">
<div class="card shadow-sm">

<div class="card-body">
<div style="max-height:400px; overflow-y:auto;">
    <table class="table table-sm table-bordered table-striped table-hover">
      <thead class="table-secondary sticky-top">

<tr>
<th>Name</th>
<th>Email</th>
<th>Role</th>
</tr>

</thead>
<tbody>

@foreach($users as $user)
<tr onclick="selectRow(this, {{ $user->id }})">
<td>{{ $user->name }}</td>
<td>{{ $user->email }}</td>
<td>{{ ucfirst($user->role) }}</td>
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

document.getElementById('selectedUserId').value = id;

}
</script>

<script>

function editUser(){

let id = document.getElementById('selectedUserId').value;

if(!id){
alert("Please select a user first");
return;
}

window.location.href = "/users/" + id + "/edit";

}

function deleteUser(){

let id = document.getElementById('selectedUserId').value;

if(!id){
alert("Please select a user first");
return;
}

if(confirm("Are you sure you want to delete this user?")){
document.getElementById('deleteForm').action = "/users/" + id;
document.getElementById('deleteForm').submit();
}

}

</script>

</div>
</div>
@endsection