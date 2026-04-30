@extends('layouts.app')

@section('content')

<div class="container mt-5">

<h5 class="mb-4">New Product</h5>
<div class="card shadow-sm">
<div class="card-body">

<form method="POST" action="/products">
    @csrf

<div class="row">

<!-- Category -->
<div class="col-md-5 mb-4">
    <label class="form-label">Category</label>

    <select name="category_id" class="form-control select2" required>

    <option value="">-- Select Category --</option>
   @foreach($categories as $category)

   <option value="{{ $category->id }}">
   {{ $category->name }}
   </option>

   @endforeach

   </select>

</div>

</select>
    <div class="col-md-5 mb-4"> 
    <label class="form-label">Product Name</label>
    <input type="text" name="name" class="form-control"  required>
    </div>

 <div class="col-md-5 mb-4">
   <label class="form-label">Unit</label>
    <select name="unit_id" class="form-control select2">
    <option value="">Select Unit</option>
    @foreach($units as $unit)
        <option value="{{ $unit->id }}">
            {{ $unit->name }} ({{ $unit->symbol }})
        </option>
    @endforeach
</select>
</div>
    <div class="col-md-5 mb-4">
    <label>Cost Price</label>
    <input type="number" step="0.01" name="cost_price" class="form-control" required>
    </div>

    <div class="col-md-5 mb-4">
    <label>Selling Price</label>
    <input type="number" step="0.01" name="selling_price" class="form-control" required>
    </div>

    <div class="col-md-5 mb-4">
    <label>Stock</label>
    <input type="number" name="stock" class="form-control" required>
    </div>

    <div class="col-md-5 mb-4">
    <label>Minimum Stock</label>
    <input type="number" name="min_stock" class="form-control" required>
    </div>

    <div class="mt-2">
    <button type="submit" class="btn btn-sm custom-pill-btn">Save</button>

    <a href="{{ route('products.index') }}" class="btn btn-sm custom-pill-btn">
    Cancel
    </a>

    </div>

</form>
</div>
</div>
@endsection