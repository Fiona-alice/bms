@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <h5 class="mb-4">New Sale</h5>

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
    <form action="{{ route('sales.store') }}" method="POST">
        @csrf

        <div class="row">
         <div class="col-md-5 mb-4">
            <label for="product" class="form-label">Product</label>
            <select name="product_id" id="product" class="form-control" required>
                <option value="">-- Select Product --</option>
                @foreach($products as $product)
                    <option value="{{ $product->id }}" data-price="{{ $product->selling_price }}">
                        {{ $product->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="col-md-5 mb-4">
            <label for="quantity" class="form-label">Quantity</label>
            <input type="number" name="quantity" id="quantity" value="1" min="1" class="form-control" required>
        </div>

        <div class="col-md-5 mb-4">
            <label for="selling_price" class="form-label">Selling Price (UGX)</label>
            <input type="number" step="0.01" name="selling_price" id="selling_price" class="form-control" required>
        </div>

        <div class="col-md-5 mb-4">
            <label for="date" class="form-label">Sale Date</label>
            <input type="date" name="date" id="date" class="form-control" required>
        </div>

       <div class="mt-3">
     <button type="submit" class="btn btn-sm custom-pill-btn">
     Save
    </button>
    <a href="{{ route('sales.index') }}" class="btn btn-sm custom-pill-btn">
    Cancel</a>
   </div>
    </form>
</div>

<script>
    const productSelect = document.getElementById('product');
    const sellingPriceInput = document.getElementById('selling_price');

    productSelect.addEventListener('change', function() {
        const selectedOption = this.options[this.selectedIndex];
        const price = selectedOption.getAttribute('data-price') || 0;
        sellingPriceInput.value = price;
    });

    // Optionally, set the default selling price to the first product when page loads
    window.addEventListener('DOMContentLoaded', function() {
        if(productSelect.options.length > 1) { // skip placeholder
            const firstOption = productSelect.options[1];
            sellingPriceInput.value = firstOption.getAttribute('data-price') || 0;
        }
    });
</script>
@endsection