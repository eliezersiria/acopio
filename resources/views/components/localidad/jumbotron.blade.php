<!-- Jumbotron Tailwind -->
<section x-data="{ show: false }" x-init="setTimeout(() => show = true, 300)" x-show="show"
    x-transition:enter="transition ease-out duration-700" x-transition:enter-start="opacity-0 translate-y-5"
    x-transition:enter-end="opacity-100 translate-y-0" class="bg-gray-500 text-white py-20 w-full">
    <div class="w-full px-4 text-center">
        <h1 class="text-4xl md:text-5xl font-bold mb-4">
            Comarcas
        </h1>
        <p class="text-lg md:text-xl mb-6">
            Bienvenido. Aquí podrá gestionar las Comarcas.
        </p>
        <div class="space-x-4">
            <a href="{{ route('localidad.agregar') }}" wire:navigate class="btn btn-primary">Agregar Comarca</a>
            <a href="{{ route('localidad.listar') }}" wire:navigate
                class="btn btn-outline text-white border-white hover:bg-white hover:text-blue-500">Lista de Comarcas</a>
        </div>
    </div>
</section>