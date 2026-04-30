@extends('layouts.app')

@section('content')

<div class="container mt-5">
    <h5 class="mb-4">New Expense</h5>

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
<form action="{{ route('expenses.store') }}" method="POST">
@csrf

<div class="row">
<div class="col-md-5 mb-4">
<label for="category" class="form-label">Category</label>
<input type="text" name="category" class="form-control" required>
</div>

<div class="col-md-5 mb-4">
<label for="amount" class="form-label">Amount</label>
<input type="number" step="0.01" name="amount" class="form-control" required>
</div>

<div class="col-md-5 mb-4">
<label for="date" class="form-label">Date</label>
<input type="date" name="date" class="form-control" required>
</div>

<div class="col-md-5 mb-4">
<label for="description" class="form-label">Description</label>
<textarea name="description" class="form-control"></textarea>
</div>

 <div class="mt-3">
     <button type="submit" class="btn btn-sm custom-pill-btn">
     Save
    </button>
    <a href="{{ route('expenses.index') }}" class="btn btn-sm custom-pill-btn">
    Cancel</a>
   </div>

</form>
</div>
</div>
@endsection