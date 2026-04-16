@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <h5 class="mb-4">Edit Purchase #{{ $purchase->id }}</h5>

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
    <form action="{{ route('purchases.update', $purchase->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="row">
        <div class="col-md-5 mb-4">
            <label>Product</label>
            <select name="product_id" class="form-select" required>
                @foreach ($products as $product)
                    <option value="{{ $product->id }}" {{ $purchase->product_id == $product->id ? 'selected' : '' }}>
                        {{ $product->name }} (Stock: {{ $product->stock }})
                    </option>
                @endforeach
            </select>
        </div>

         <div class="col-md-5 mb-4">
            <label class="form-label">Total</label>
            <input type="number" name="total_cost" class="form-control" value="{{ $purchase->total_cost }}" required>
        </div>

        <div class="col-md-5 mb-4">
            <label class="form-label">Quantity</label>
            <input type="number" name="quantity" class="form-control" value="{{ $purchase->quantity }}" required>
        </div>

       <div class="col-md-5 mb-4">
            <label class="form-label">Cost Price (UGX)</label>
            <input type="number" name="cost_price" class="form-control" step="0.01" value="{{ $purchase->cost_price }}" required>
        </div>

        <div class="col-md-5 mb-4">
            <label class="form-label">Date</label>
            <input type="date" name="date" class="form-control" value="{{ $purchase->date }}" required>
        </div>

        <div class="mt-2">
        <button type="submit" class="btn btn-sm custom-pill-btn">Save</button>
        <a href="{{ route('purchases.index') }}" class="btn btn-sm custom-pill-btn">Cancel</a>
        </div>

    </form>
</div>
</div>
@endsection