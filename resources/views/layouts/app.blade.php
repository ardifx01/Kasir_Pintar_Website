<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Kasir Pintar')</title>
    <link rel="stylesheet" href="/storage/assets/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="/storage/assets/bootstrap/css/general.css">
    @livewireStyles
</head>
<body>
    <div class="container-fluid p-0">
        @yield('content')
    </div>
    @yield('additional')
    @livewireScripts
    <script src="/storage/assets/bootstrap/js/bootstrap.bundle.min.js"></script>
</body>
</html>
