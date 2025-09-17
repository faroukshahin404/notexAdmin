<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>@yield('title','Login') | Admin</title>
    <link rel="icon" type="image/png" href="{{ asset('assets/images/favicon.png') }}" />
    <link rel="apple-touch-icon" href="{{ asset('assets/images/logo.png') }}" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" />
    <link rel="stylesheet" href="{{ asset('assets/css/adminlte.min.css') }}" />
</head>
<body class="login-page bg-body-secondary">
<div class="login-box">
  <div class="card card-outline card-primary">
    <div class="card-header text-center">
      <a href="#" class="h1 d-flex justify-content-center align-items-center gap-2">
        <img src="{{ asset('assets/images/logo.png') }}" alt="Logo" style="height:36px;width:auto;object-fit:contain;" />
        <span>Admin</span>
      </a>
    </div>
    <div class="card-body">
      @yield('content')
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.min.js"></script>
<script src="{{ asset('assets/js/adminlte.min.js') }}"></script>
</body>
</html>


