<!-- SIDEBAR -->
<div class="w-64 min-h-screen bg-base-200 flex flex-col drawer lg:drawer-open">

    <!-- Logo / Título -->
    <div class="xl:hidden text-2xl font-bold flex items-center">
        <x-heroicon-o-home class="w-8 h-8 text-primary" />
        Panel
    </div>

    <!-- Menu -->
    <ul class="menu bg-base-200 w-full">
        <x-localidad.sidebar-contenido />
    </ul>

</div>