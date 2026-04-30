@extends('layouts.app')

@section('content')
@section('title', 'Categories - BMSystem')

<h5>Categories</h5><br>

<div class="d-flex justify-content-between mb-3">

<div class="d-flex justify-content-between align-items-center mb-3">

<div class="d-flex gap-2">

<a href="/categories/create" class="btn btn-sm custom-pill-btn">
New
</a>

<button onclick="editCategory()" class="btn btn-sm custom-pill-btn">
Edit
</button>

<button onclick="deleteCategory()" class="btn btn-sm custom-pill-btn">
Delete
</button>

<a href="{{ route('categories.export') }}" 
class="btn btn-sm custom-pill-btn">
Export
</a>

</div>
</div>

<form method="GET" action="/categories" id="searchForm">
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

<input type="hidden" id="selectedCategoryId">
<div class="card shadow-sm">

<div class="card-body">

<div style="max-height:400px; overflow-y:auto;">

    <table class="table table-sm table-bordered table-striped table-hover">

      <thead class="table-secondary sticky-top">

<tr>
<th>ID</th>
<th>Name</th>
</tr>

</thead>

<tbody>

@foreach($categories as $category)

<tr onclick="selectRow(this, {{ $category->id }})">

<td>{{ $category->id }}</td>

<td>{{ $category->name }}</td>

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

document.getElementById('selectedCategoryId').value = id;

}
</script>

<script>

function editCategory(){

let id = document.getElementById('selectedCategoryId').value;

if(!id){
alert("Please select a category first");
return;
}

window.location.href = "/categories/" + id + "/edit";

}

function deleteCategory(){

let id = document.getElementById('selectedCategoryId').value;

if(!id){
alert("Please select a category first");
return;
}

if(confirm("Are you sure you want to delete this category?")){
document.getElementById('deleteForm').action = "/categories/" + id;
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
    }, 1000); // 0.5 second delay
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