@extends('layouts.app')

@section('content')

<div class="container mt-5">
      <h5 class="mb-4">Edit Hire: {{ $rental->name }}</h5>

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

   <form action="{{ route('rentals.update', $rental->id) }}" method="POST">
        @csrf
        @method('PUT')

       <div class="row">
        <div class="col-md-5 mb-4">
            <label>Product</label>
            <select name="product_id" class="form-control">
                @foreach($products as $product)
                    <option value="{{ $product->id }}"
                        {{ $rental->product_id == $product->id ? 'selected' : '' }}>
                        {{ $product->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="col-md-5 mb-4">
            <label>Client</label>
            <select name="client_id" class="form-control">
                @foreach($clients as $client)
                    <option value="{{ $client->id }}"
                        {{ $rental->client_id == $client->id ? 'selected' : '' }}>
                        {{ $client->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="col-md-5 mb-4">
            <label>Quantity</label>
            <input type="number" name="quantity" class="form-control"
                   value="{{ $rental->quantity }}">
        </div>

        <div class="col-md-5 mb-4">
            <label>Rental Price</label>
            <input type="number" name="rental_price" class="form-control"
                   value="{{ $rental->rental_price }}">
        </div>

        <div class="col-md-5 mb-4">
            <label>Date Out</label>
            <input type="date" name="date_out" class="form-control"
                   value="{{ $rental->date_out }}">
        </div>

        <div class="col-md-5 mb-4">
            <label>Expected Return</label>
            <input type="date" name="expected_return" class="form-control"
                   value="{{ $rental->expected_return }}">
        </div>

        <div class="mt-2">
        <button type="submit" class="btn btn-sm custom-pill-btn">Save</button>
        <a href="{{ route('rentals.index') }}" class="btn btn-sm custom-pill-btn">Cancel</a>
        </div>
    </form>
</div>
</div>
@endsection