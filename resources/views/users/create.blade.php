@extends('layouts.app')

@section('content')

<div class="container mt-5">
    <h5 class="mb-4">New User</h5>

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

<form action="{{ route('users.store') }}" method="POST">
@csrf

<div class="row">
<div class="col-md-5 mb-4">
<label for="name" class="form-label">Name</label>
<input type="text" name="name" class="form-control" required>
</div>

<div class="col-md-5 mb-4">
<label for="email" class="form-label">Email</label>
<input type="email" name="email" class="form-control" required>
</div>

<div class="col-md-5 mb-4">
<label for="password" class="form-label">Password</label>
<input type="password" name="password" class="form-control" required>
</div>

<div class="col-md-5 mb-4">
<label for="role" class="form-label">Role</label>
<select name="role" class="form-control">
<option value="admin">Admin</option>
<option value="staff">Staff</option>
</select>
</div>

   <div class="mt-3">
     <button type="submit" class="btn btn-sm custom-pill-btn">
     Save
    </button>
    <a href="{{ route('users.index') }}" class="btn btn-sm custom-pill-btn">
    Cancel</a>
   </div>

</form>
</div>
</div>

@endsection