@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <h5 class="mb-4">Edit Expense #{{ $expense->id }}</h5>

       <!-- Validation Errors -->
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
    <form action="{{ route('expenses.update', $expense->id) }}" method="POST">
        @csrf
        @method('PUT')

          <div class="row">
          <div class="col-md-5 mb-4">
            <label for="category" class="form-label">Category</label>
            <input type="text" name="category" id="category"
                   value="{{ old('category', $expense->category) }}" class="form-control" required>
        </div>

        <div class="col-md-5 mb-4">
            <label for="amount" class="form-label">Amount (UGX)</label>
            <input type="number" name="amount" id="amount" step="0.01"
                   value="{{ old('amount', $expense->amount) }}" class="form-control" required>
        </div>

        <div class="col-md-5 mb-4">
            <label for="date" class="form-label">Date</label>
            <input type="date" name="date" id="date"
                   value="{{ old('date', $expense->date) }}" class="form-control" required>
        </div>

        <div class="col-md-5 mb-4">
            <label for="description" class="form-label">Description</label>
            <textarea name="description" id="description" rows="3" class="form-control">{{ old('description', $expense->description) }}</textarea>
        </div>

      <div class="mt-2">
        <button type="submit" class="btn btn-sm custom-pill-btn">Save</button>
        <a href="{{ route('expenses.index') }}" class="btn btn-sm custom-pill-btn">Cancel</a>
        </div>

     
    </form>
</div>
@endsection