<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>@yield('title', 'Mi Aplicación')</title>
    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('images/icons/lock.png') }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>

<body class="container mx-auto">
    <!-- Main content -->
    <div>
        @yield('content')
    </div>
    @livewireScripts
</body>

</html>