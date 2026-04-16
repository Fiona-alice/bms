@extends('layouts.app')

@section('content')

<div class="container mt-5">
<h4>New Purchase</h4>

<div class="card shadow-sm">
<div class="card-body">

<form action="{{ route('purchases.store') }}" method="POST">

@csrf

<div class="row">

<div class="col-md-5 mb-4">
<label class="form-label">Product</label>
<select name="product_id" class="form-control" required>

@foreach($products as $product)

<option value="{{ $product->id }}">
{{ $product->name }}
</option>

@endforeach

</select>
</div>

<div class="col-md-5 mb-4">
<label class="form-label">Total</label>
<input type="number" name="total_cost" class="form-control" required>
</div>

<div class="col-md-5 mb-4">
<label class="form-label">Quantity</label>
<input type="number" name="quantity" class="form-control" required>
</div>

<div class="col-md-5 mb-4">
<label class="form-label">Cost Price</label>
<input type="number" name="cost_price" class="form-control"  readonly>
</div>

<div class="col-md-5 mb-4">
<label class="form-label">Date</label>
<input type="date" name="date" class="form-control" required>
</div>


<div class="mt-3">
<button type="submit" class="btn btn-sm custom-pill-btn">
Save </button>

<a href="{{ route('purchases.index') }}" class="btn btn-sm custom-pill-btn">
Cancel
</a>
</div>
</form>

</div>
</div>
</div>

<script>
function calculate(){
    let qty = parseFloat(document.querySelector('[name="quantity"]').value);
    let total = parseFloat(document.querySelector('[name="total_cost"]').value);

    if(qty && total){
        let unit = total / qty;
        document.querySelector('[name="cost_price"]').value = unit.toFixed(2);
    }
}

// trigger when user types
document.querySelector('[name="quantity"]').addEventListener('input', calculate);
document.querySelector('[name="total_cost"]').addEventListener('input', calculate);
</script>

@endsection