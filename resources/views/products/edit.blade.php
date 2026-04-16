@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <h5 class="mb-4">Edit Product: {{ $product->name }}</h5>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif


     <div class="card shadow-sm">
     <div class="card-body">

    <form action="{{ route('products.update', $product->id) }}" method="POST">
        @csrf
        @method('PUT')
      
        <div class="row">
        <div class="col-md-5 mb-4">
            <label class="form-label">Product Name</label>
            <input type="text" name="name" value="{{ $product->name }}" class="form-control" required>
        </div>

        <div class="col-md-5 mb-4">
            <label class="form-label">Category</label>
            <select name="category_id" class="form-control" required>
                @foreach ($categories as $category)
                    <option value="{{ $category->id }}" {{ $product->category_id == $category->id ? 'selected' : '' }}>
                        {{ $category->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="col-md-5 mb-4">
            <label class="form-label">Cost Price</label>
            <input type="number" name="cost_price" step="0.01" value="{{ $product->cost_price }}" class="form-control" required>
        </div>

        <div class="col-md-5 mb-4">
            <label class="form-label">Selling Price</label>
            <input type="number" name="selling_price" step="0.01" value="{{ $product->selling_price }}" class="form-control" required>
        </div>

        <div class="col-md-5 mb-4">
            <label class="form-label">Stock</label>
            <input type="number" name="stock" value="{{ $product->stock }}" class="form-control" required>
        </div>

        <div class="col-md-5 mb-4">
            <label class="form-label">Minimum Stock</label>
            <input type="number" name="min_stock" value="{{ $product->min_stock }}" class="form-control" required>
        </div>


        <div class="mt-2">
        <button type="submit" class="btn btn-sm custom-pill-btn">Save</button>
        <a href="{{ route('products.index') }}" class="btn btn-sm custom-pill-btn">Cancel</a>
        </div>
     
    </form>
</div>
</div>
@endsection