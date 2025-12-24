<li>
    <a>
        <x-heroicon-o-cog-6-tooth class="text-primary w-8 h-8" />
        <span class="text-xl">Panel</span>
    </a>
</li>

<li>
    <a href="{{ route('localidad.agregar') }}" wire:navigate
        class="hover:bg-base-300 hover:border-base-400 py-4 px-4 block border border-base-300 rounded-lg {{ Route::is('localidad.agregar') ? 'bg-primary text-white' : 'bg-base-100' }} shadow-sm hover:shadow-md transition-all duration-200">
        <div class="flex items-center space-x-3">
            <x-heroicon-o-plus-circle class="w-5 h-5" />
            <span>Agregar Comarca</span>
        </div>
    </a>
</li>

<li>
    <a href="{{ route('localidad.listar') }}" wire:navigate
        class="hover:bg-base-300 hover:border-base-400 py-4 px-4 block border border-base-300 rounded-lg {{ Route::is('localidad.listar') || Route::is('localidad.editar') ? 'bg-primary text-white' : 'bg-base-100' }} shadow-sm hover:shadow-md transition-all duration-200">
        <div class="flex items-center space-x-3">
            <x-heroicon-o-map-pin class="w-5 h-5" />
            <span>Lista de Comarcas</span>
        </div>
    </a>
</li>