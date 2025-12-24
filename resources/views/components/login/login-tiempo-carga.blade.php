@php
    $start = defined('LARAVEL_START') ? LARAVEL_START : microtime(true);
    $tiempo = round((microtime(true) - $start) * 1000);
@endphp

<div class="mb-2 text-xs text-gray-500 flex justify-center items-center">
    Cargado en {{ $tiempo }} segundos <x-heroicon-o-clock class="w-4 h-4" />
</div>