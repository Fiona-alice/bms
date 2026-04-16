@extends('layouts.app')

@section('content')

<h4>Edit Category</h4><br>

<form method="POST" action="/categories/{{ $category->id }}">

@csrf
@method('PUT')

<div class="mb-3">

<label>Category Name</label><br>

<input type="text"
name="name"
value="{{ $category->name }}"
required>

</div>

<button class="btn btn-sm custom-pill-btn">Save</button>
<a href="{{ route('categories.index') }}" class="btn btn-secondary">Cancel</a>
</form>

@endsection