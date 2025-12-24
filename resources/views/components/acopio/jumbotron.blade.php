<!-- Jumbotron Tailwind -->
<section class="bg-gray-500 text-white py-20 w-full">
    <div class="w-full px-4 text-center">
        <h1 class="text-4xl md:text-5xl font-bold mb-4">
            Productores
        </h1>
        <p class="text-lg md:text-xl mb-6">
            Bienvenido. Aquí podrá gestionar los Productores.
        </p>
        <div class="md:flex-row md:space-y-0 md:space-x-4">
            <a href="{{ route('productor.agregar') }}" wire:navigate class="btn btn-primary">Agregar Productor</a>
            <a href="{{ route('productor.listar') }}" wire:navigate
                class="btn btn-outline text-white border-white hover:bg-white hover:text-blue-500">
                Lista de Productores
            </a>
        </div>
    </div>
</section>