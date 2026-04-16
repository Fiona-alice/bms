<!DOCTYPE html>
<html>
<head>
<title>BM System</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

<style>
body {
    height: 100vh;
    display: flex;
    justify-content: center;
    align-items: center;
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    
}

.login-card {
    background: rgba(240, 240, 240, 0.95); /* semi-transparent black */
    border-radius: 50%; /* circular shape */
    width: 400px;
    height: 450px;
    padding: 40px;
    box-shadow:
        0 0 20px rgba(255,215,0,0.6),
        0 0 40px rgba(255,215,0,0.4); /* glow effect */
        0 0 60px rgba(255, 215, 0, 0.2);
    backdrop-filter:blur(6px);
    display:flex;
    flex-direction:column;
    justify-content:center;
    color:#333;
}

.login-card form {
    display: flex;
    flex-direction: column;
    gap: 15px;
}

.login-card input.form-control {
    background: #fff;
    border: 1px solid #ddd;
    border-radius: 25px;
    color: #333;
    padding-left: 40px;
}

.login-card input.form-control::placeholder {
    color: #999;
}

.login-card .form-check-label,
.login-card a {
    color: #555;
    font-size: 0.9rem;
}

.login-btn {
    background: #0d6efd;
    border: none;
    border-radius: 25px;
    padding: 8px 50px;
    font-weight: bold;
    color: #fff;
    transition: 0.3s;
}

.login-btn:hover {
    background: #0b5ed7;
}

.icon {
    position: absolute;
    margin-left: 10px;
    margin-top: 10px;
    color: #666;
}
.input-group {
    position: relative;
}

.system-title {
    text-align: center;
    font-weight: bold;
    font-size: 28px;
    margin-bottom: 10px;
    color: #000;
}

.system-subtitle {
    text-align: center;
    font-size: 14px;
    color: #666;
    margin-bottom: 20px;
}
</style>
</head>

<body>
<body style="background-image: url('{{ asset('images/image.jpg') }}'); background-size: cover;">
<div class="login-card">

<div class="system-title">BMSystem</div>
<div class="system-subtitle">Sign in to continue</div>

<form method="POST" action="{{ route('login') }}">
@csrf

@if ($errors->any())
<div class="alert alert-danger">
{{ $errors->first() }}
</div>
@endif

<div class="input-group">
    <span class="icon"><i class="bi bi-person"></i></span>
    <input type="text" name="login" class="form-control" placeholder="Email or Username" required>
</div>

<div class="input-group">
    <span class="icon"><i class="bi bi-lock"></i></span>
    <input type="password" name="password" class="form-control" placeholder="Password" required>
</div>

<div class="d-flex justify-content-between mb-3">
    <div class="form-check">
        <input class="form-check-input" type="checkbox" name="remember" id="remember">
        <label class="form-check-label" for="remember">Remember Me</label>
    </div>

    @if (Route::has('password.request'))
    <a href="{{ route('password.request') }}">Forgot Password?</a>
    @endif
</div>

<div class="text-center">
    <button type="submit" class="btn login-btn">Login</button>
    
</div>

</form>

</div>

<!-- Bootstrap icons -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">

</body>
</html>