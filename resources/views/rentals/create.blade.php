@extends('layouts.app')

@section('content')

<div class="container mt-5">

<h4 class="mb-4">New Hire</h4>

<div class="card shadow-sm">

<div class="card-body">

<form action="{{ route('rentals.store') }}" method="POST">

@csrf

<div class="row">

<!-- Product -->
<div class="col-md-5 mb-4">
<label class="form-label">Product</label>
<select name="product_id" class="form-control" required>

<option value="">-- Select Product --</option>

@foreach($products as $product)

<option value="{{ $product->id }}">
{{ $product->name }} (Stock: {{ $product->stock }})
</option>

@endforeach

</select>
</div>

<!-- Client -->
<div class="col-md-5 mb-4">
<label class="form-label">Client</label>
<select name="client_id" class="form-control" required>

<option value="">-- Select Client --</option>

@foreach($clients as $client)
<option value="{{ $client->id }}">
    {{ $client->name }} {{ $client->phone ? '(' . $client->phone . ')' : '' }}
</option>
@endforeach

</select>
</div>

<!-- Quantity -->
<div class="col-md-5 mb-4">
<label class="form-label">Quantity</label>
<input type="number" name="quantity" class="form-control" min="1" required>
</div>

<!-- Rental Price -->
<div class="col-md-5 mb-4">
<label class="form-label">Rental Price</label>
<input type="number" step="0.01" name="rental_price" class="form-control" required>
</div>

<!-- Date Out -->
<div class="col-md-5 mb-4">
<label class="form-label">Date Out</label>
<input type="date" name="date_out" class="form-control" required>
</div>


<div class="col-md-5 mb-4">
<label class="form-label">Expected Return Date</label>
<input type="date" name="expected_return" class="form-control" required>
</div>

<div class="mt-3">
<button type="submit" class="btn btn-sm custom-pill-btn">Save</button>

<a href="{{ route('rentals.index') }}" class="btn btn-sm custom-pill-btn">
Cancel</a>

</div>

</form>

</div>

</div>

</div>

@endsection