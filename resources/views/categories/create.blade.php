@extends('layouts.app')

@section('content')

<h4>Add Category</h4><br>

<form method="POST" action="/categories">

@csrf

<div class="mb-3">

<label>Category Name</label><br>


<input type="text"
name="name"
required><br><br>

</div>

<button class="btn btn-sm custom-pill-btn">Save</button>
<a href="{{ route('categories.index') }}" class="btn btn-sm custom-pill-btn">Cancel</a>

</form>

@endsection