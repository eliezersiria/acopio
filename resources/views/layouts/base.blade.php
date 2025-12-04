<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>@yield('title', 'Mi Aplicación')</title>
    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('images/icons/vaca.png') }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>

<body>

    <!-- NAVBAR -->
    <div class="w-full h-16 flex items-center">
        @yield('navbar')
    </div>
    <!-- CONTENEDOR PRINCIPAL -->
    <div class="flex min-h-screen">

        <!-- SIDEBAR (20%) - Oculta en móviles y tablets -->
        <div class="w-1/5 hidden xl:block">
            @yield('sidebar')
        </div>

        <!-- MAIN CONTENT (100% en móvil y tablet, 80% en desktop) -->
        <div class="w-full lg:w-4/5 bg-base-200">
            @yield('content')
        </div>

    </div>

    <!-- FOOTER -->
    <div class="w-full h-14 bg-gray-800 text-white flex items-center justify-center">
        Derechos reservados 2025
    </div>



    @livewireScripts
</body>

</html>