@extends('layouts.app')

@section('content')

<div class="container mt-4">
<h5>New Stock Adjustment</h5>

<div class="card shadow-sm">
<div class="card-body">

<form action="{{ route('adjustments.store') }}" method="POST">
@csrf

<div class="row">

<!-- Product -->
<div class="col-md-5 mb-4">
<label>Product</label>
<select name="product_id" class="form-control" required>
@foreach($products as $product)
<option value="{{ $product->id }}">
{{ $product->name }} (Stock: {{ $product->stock }})
</option>
@endforeach
</select>
</div>

<!-- Type -->
<div class="col-md-5 mb-4">
<label>Adjustment Type</label>
<select name="type" class="form-control" required>
<option value="loss">Loss</option>
<option value="damage">Damage</option>
<option value="theft">Theft</option>
<option value="gain">Gain</option>
<option value="correction">Correction</option>
</select>
</div>

<!-- Quantity -->
<div class="col-md-5 mb-4">
<label>Quantity ({{ $product->unit->symbol ?? '' }})</label>
<input type="number" name="quantity" class="form-control" required>
</div>

<!-- Date -->
<div class="col-md-5 mb-4">
<label>Date</label>
<input type="date" name="date" class="form-control" required>
</div>

<!-- Reason -->
<div class="col-md-5 mb-4">
<label>Reason (optional)</label>
<textarea name="reason" class="form-control"></textarea>
</div>

</div>

<div class="mt-3">
<button type="submit" class="btn btn-sm custom-pill-btn">Save</button>
<a href="{{ route('adjustments.index') }}" class="btn btn-sm custom-pill-btn">Cancel</a>
</div>

</form>

</div>
</div>
</div>

@endsection