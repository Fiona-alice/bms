@extends('layouts.app')

@section('content')

<div class="container mt-5">

<h4>New Client</h4>

<div class="card shadow-sm">
<div class="card-body">

<form method="POST" action="{{ route('clients.store') }}">
@csrf

<div class="row">

<div class="col-md-5 mb-4">
    <label class="form-label">Name</label>
    <input type="text" name="name" class="form-control" required>
</div>

<div class="col-md-5 mb-4">
    <label class="form-label">Phone</label>
    <input type="text" name="phone" class="form-control" required>
</div>

<div class="col-md-5 mb-4">
    <label class="form-label">NIN NO</label>
    <input type="text" name="nin" class="form-control">
</div>

<div class="col-md-5 mb-4">
    <label class="form-label">Email</label>
    <input type="email" name="email" class="form-control">
</div>

<div class="col-md-5 mb-4">
    <label class="form-label">Address</label>
    <input type="text" name="address" class="form-control" required>
</div>

<div class="mt-3">
<button type="submit" class="btn btn-sm custom-pill-btn">
Save </button>

<a href="{{ route('clients.index') }}" class="btn btn-sm custom-pill-btn">
Cancel
</a>
</div>

</form>

</div>
</div>

</div>

@endsection