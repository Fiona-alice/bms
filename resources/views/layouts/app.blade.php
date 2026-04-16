<!DOCTYPE html>
<html>
<head>
<link rel="icon" type="image/png" href="{{ asset('images/logo.jpg') }}">
<title>@yield('title', 'BMSystem')</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
$(document).ready(function() {
    $('.select2').select2({
        allowClear: true,
        width: '100%',
        minimumResultsForSearch: 0
    });
});
</script>

<style>
.select2-container--default .select2-selection--single {
    height: 38px;
    padding: 6px 12px;
    border-radius: 6px;
    border: 1px solid #ced4da;
}
</style>
<style>

body{
    background:#f4f6f9;
}

.sidebar{
    width:180px;
    height:100vh;
    position:fixed;
    background:#5e5e60;
    color:white;
    transition: width 0.3s ease;
    overflow-x: hidden;
}

.sidebar-header{
    text-align:left;
    margin-left:20px;
    padding:15px 0;
    border-bottom:1px solid #555;
}

.sidebar a{
    display: flex;
    align-items: center;
    gap: 8px;
    color: #e4e6eb;
    padding: 8px 18px;  
    text-decoration: none;
    font-size: 14px;
    border-radius: 6px;
    margin: 3px 8px;    
    transition: all 0.2s ease;
}

.sidebar a:hover{
     background: #495057;
    color: white;
}

.sidebar a.active {
    background: #0d6efd;
    color: white;
}

.sidebar.collapsed {
    width: 70px;
}
.sidebar.collapsed a span {
    display: none;
}

.sidebar a i {
    font-size: 18px;
}

.main-content{
    margin-left:180px;
    padding:30px;
    transition: all 0.3s ease;
}

.main-content.expanded {
    margin-left: 70px;
}

.topbar{
    background:white;
    padding:10px 20px;
    border-bottom:1px solid #ddd;
    margin-bottom:15px; 
     height: 50px;   
}

.custom-outline{
border:1px solid #555;
color:#333;
}

.custom-outline:hover{
background:#f1f1f1;
}

tbody tr{
cursor:pointer;
}

.custom-pill-btn {
    border-radius: 10px;
    border: 1px solid #555; 
    color: #0d6efd;
    background-color: #f6f8fa;
    padding: 4px 12px;
    font-size: 13px;
    transition: all 0.2s ease;
    font-weight: bold;
}

.custom-pill-btn:hover {
    background-color: #f1f1f1;
   
}
.custom-select {
    appearance: none;
    -webkit-appearance: none;
    -moz-appearance: none;
    padding-right: 35px; /* space for icon */
}

.custom-arrow {
    position: absolute;
    top: 50%;
    right: 12px;  
    transform: translateY(-50%);
    pointer-events: none;
    color: #6c757d;
    font-size: 14px;
}
.user-avatar {
    width: 34px;
    height: 34px;
    background: #0d6efd;
    color: white;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: bold;
    cursor: pointer;
    font-size: 13px;
}

.user-avatar:hover {
    background: #0b5ed7;
}

</style>

</head>

<body>

<div class="sidebar">

<div class="sidebar-header">
    <i class="bi bi-list menu-toggle" id="toggleSidebar"></i>
</div>

<a href="/dashboard" class="active"><i class="bi bi-speedometer2 me-2"></i> <span>KPI Dashboard</span> </a>
<a href="/products"> <i class="bi bi-box-seam me-2"></i><span> Products</span> </a>
<a href="/categories"> <i class="bi bi-tags me-2"></i> <span>Categories</span> </a>
<a href="/rentals"> <i class="bi bi-calendar-check me-2"></i> <span>Rental/Hire</span> </a>
<a href="/purchases"> <i class="bi bi-cart-plus me-2"></i> <span>Purchases</span> </a>
<a href="/sales"> <i class="bi bi-cash-stack me-2"></i> <span>Sales</span> </a>
<a href="/adjustments"> <i class="bi bi-box-seam me-2"></i> <span>Adjustments</span> </a>
<a href="/expenses"> <i class="bi bi-receipt me-2"></i> <span>Expenses</span> </a>
<a href="/clients"> <i class="bi bi-people me-2"></i> <span>Clients</span> </a>
<a href="/users">  <i class="bi bi-person-gear me-2"></i> <span>Manage Users</span> </a>

</div>



<div class="main-content">

<div class="topbar d-flex justify-content-between align-items-center">

    <strong>BMSystem</strong>

    <div class="dropdown">
        <div class="user-avatar dropdown-toggle" data-bs-toggle="dropdown">
            {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
        </div>

        <ul class="dropdown-menu dropdown-menu-end">
            <li>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="dropdown-item text-danger">
                        <i class="bi bi-box-arrow-right me-2"></i> Logout
                    </button>
                </form>
            </li>
        </ul>
    </div>

</div>


@yield('content')

</div>

<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>
const toggleBtn = document.getElementById('toggleSidebar');
const sidebar = document.querySelector('.sidebar');
const mainContent = document.querySelector('.main-content');

toggleBtn.addEventListener('click', function () {
    sidebar.classList.toggle('collapsed');
    mainContent.classList.toggle('expanded');
});
</script>

</body>
</html>