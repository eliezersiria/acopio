<li>
    <a>
        <x-heroicon-o-cog-6-tooth class="text-primary w-8 h-8" />
        <span class="text-xl">Productores</span>
    </a>
</li>

<li>
    <a href="{{ route('productor.agregar') }}" wire:navigate
        class="hover:bg-base-300 hover:border-base-400 py-4 px-4 block border border-base-300 rounded-lg {{ Route::is('productor.agregar') ? 'bg-primary text-white' : 'bg-base-100' }} shadow-sm hover:shadow-md transition-all duration-200">
        <div class="flex items-center space-x-3">
            <img src="{{ asset('storage/images/icons/granjero.png') }}"/>
            <span>Agregar Productores</span>
        </div>
    </a>
</li>

<li>
    <a href="{{ route('productor.listar') }}" wire:navigate
        class="hover:bg-base-300 hover:border-base-400 py-4 px-4 block border border-base-300 rounded-lg {{ Route::is('productor.listar') || Route::is('productor.editar') ? 'bg-primary text-white' : 'bg-base-100' }} shadow-sm hover:shadow-md transition-all duration-200">
        <div class="flex items-center space-x-3">
            <img src="{{ asset('storage/images/icons/portapapeles.png') }}"/>
            <span>Lista de Productores</span>
        </div>
    </a>
</li>