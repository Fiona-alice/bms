<!DOCTYPE html>
<html>
<head>

<title>Register User</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

<style>

body{
    background:#f4f6f9;
}

.register-card{
    max-width:420px;
    margin:auto;
    margin-top:12vh;
}

.system-title{
    text-align:center;
    font-weight:bold;
    font-size:26px;
    margin-bottom:8px;
}

.register-btn{
    width:140px;
}

</style>

</head>

<body>

<div class="container">

<div class="card shadow register-card">

<div class="card-body">

<div class="system-title">
BMSystem
</div>

<br>

<form method="POST" action="{{ route('register') }}">
@csrf

@if ($errors->any())
<div class="alert alert-danger">
{{ $errors->first() }}
</div>
@endif

<div class="mb-3">

<label class="form-label">Name</label>

<input type="text"
name="name"
class="form-control"
placeholder="name" required>

</div>

<div class="mb-3">

<label class="form-label">UserName</label>

<input type="text"
name="username"
class="form-control"
placeholder="Username" required>

</div>

<div class="mb-3">

<label class="form-label">Email</label>

<input type="email"
name="email"
class="form-control"
placeholder="Email (optional)">

</div>

<div class="mb-3">

<label class="form-label">Password</label>

<input type="password"
name="password"
class="form-control"
required>

</div>

<div class="mb-3">

<label class="form-label">Confirm Password</label>

<input type="password"
name="password_confirmation"
class="form-control"
required>

</div>

<div class="text-center">

<button type="submit" class="btn btn-success register-btn">
Register
</button>

</div>

<div class="text-center mt-3">

<a href="{{ route('login') }}" style="text-decoration:none;">
Back to Login
</a>

</div>

</form>

</div>

</div>

</div>

</body>
</html>