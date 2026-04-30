@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <h5 class="mb-4">Edit Sale{{ $sale->id }}</h5>

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

    <form action="{{ route('sales.update', $sale->id) }}" method="POST">
        @csrf
        @method('PUT')

         <div class="row">
          <div class="col-md-5 mb-4">
            <label for="product_id" class="form-label">Product</label>
            <select name="product_id" id="product_id" class="form-control"  required>
                <option value="">-- Select Product --</option>
                @foreach ($products as $product)
                    <option value="{{ $product->id }}" {{ $sale->product_id == $product->id ? 'selected' : '' }}>
                        {{ $product->name }} (Stock: {{ $product->stock }}, Cost: UGX {{ number_format($product->cost_price) }})
                    </option>
                @endforeach
            </select>
        </div>

         <div class="col-md-5 mb-4">
            <label for="quantity" class="form-label">Quantity</label>
            <input type="number" name="quantity" id="quantity" min="1" value="{{ $sale->quantity }}" class="form-control"  required>
        </div>

         <div class="col-md-5 mb-4">
            <label for="selling_price" class="form-label">Selling Price (UGX)</label>
            <input type="number" name="selling_price" id="selling_price" step="0.01" value="{{ $sale->selling_price }}" class="form-control" required>
        </div>

         <div class="col-md-5 mb-4">
            <label for="date" class="form-label">Date</label>
            <input type="date" name="date" id="date" value="{{ $sale->date }}" class="form-control"  required>
        </div>

        <div class="mt-2">
        <button type="submit" class="btn btn-sm custom-pill-btn">Save</button>
        <a href="{{ route('sales.index') }}" class="btn btn-sm custom-pill-btn">Cancel</a>
        </div>

    </form>
</div>
@endsection