<!-- SIDEBAR -->
<div class="w-64 min-h-screen bg-base-200 p-4 flex flex-col drawer lg:drawer-open" x-data="{ show: false }"
    x-init="setTimeout(() => show = true, 300)" x-show="show" x-transition:enter="transition ease-out duration-700"
    x-transition:enter-start="opacity-0 translate-x-5" x-transition:enter-end="opacity-100 translate-x-0">

    <!-- Logo / Título -->
    <div class="xl:hidden text-2xl font-bold mb-8 flex items-center gap-2">
        <x-heroicon-o-home class="w-8 h-8 text-primary" />
        Panel
    </div>

    <!-- Menu -->
    <ul class="menu bg-base-200 w-full">
        <x-inicio.sidebar-contenido />
    </ul>


</div>