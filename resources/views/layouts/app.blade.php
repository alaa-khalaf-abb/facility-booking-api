<!DOCTYPE html>
<html>
<head>
    <title>Facility Booking</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        body { background: #f8fafc; }
        .navbar-brand { font-weight: bold; letter-spacing: 1px; }
    </style>
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-primary mb-4">
    <div class="container">
        <a class="navbar-brand" href="/">Facility Booking</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                @auth
                    <li class="nav-item"><a class="nav-link" href="/my-bookings">My Bookings</a></li>
                    <li class="nav-item"><a class="nav-link" href="/bookings/create">Book Resource</a></li>
                    <li class="nav-item"><form method="POST" action="{{ route('logout') }}">@csrf <button class="btn btn-link nav-link" type="submit">Logout</button></form></li>
                @else
                    <li class="nav-item"><a class="nav-link" href="/login">Login</a></li>
                    <li class="nav-item"><a class="nav-link" href="/register">Register</a></li>
                @endauth
            </ul>
        </div>
    </div>
</nav>
<div class="container mb-5">
    @yield('content')
</div>
@stack('scripts')
<script>
    // Set CSRF token for all AJAX requests
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
</script>
</body>
</html>
