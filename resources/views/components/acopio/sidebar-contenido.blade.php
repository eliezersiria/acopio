<li>
    <a>
        <x-heroicon-o-cog-6-tooth class="text-primary w-8 h-8" />
        <span class="text-xl">Acopios</span>
    </a>
</li>

<li>
    <a href="{{ route('acopio')}}" wire:navigate
        class="hover:bg-base-300 hover:border-base-400 py-4 px-4 block border border-base-300 rounded-lg {{ Route::is('acopio') ? 'bg-primary text-white' : 'bg-base-100' }} shadow-sm hover:shadow-md transition-all duration-200">
        <div class="flex items-center space-x-3">
            <img src="{{ asset('images/icons/portapapeles.png') }}"/>
            <span>Reporte de esta semana</span>
        </div>
    </a>
</li>

<li>
    <a href="{{ route('acopio.resumen.semanal') }}" wire:navigate
        class="hover:bg-base-300 hover:border-base-400 py-4 px-4 block border border-base-300 rounded-lg {{ Route::is('acopio.resumen.semanal') ? 'bg-primary text-white' : 'bg-base-100' }} shadow-sm hover:shadow-md transition-all duration-200">
        <div class="flex items-center space-x-3">
            <img src="{{ asset('images/icons/tanque-de-leche-2.png') }}"/>
            <span>Resumen Semanal</span>
        </div>
    </a>
</li>

<li>
    <a href="{{ route('acopio.recibos') }}" wire:navigate
        class="hover:bg-base-300 hover:border-base-400 py-4 px-4 block border border-base-300 rounded-lg {{ Route::is('acopio.recibos') ? 'bg-primary text-white' : 'bg-base-100' }} shadow-sm hover:shadow-md transition-all duration-200">
        <div class="flex items-center space-x-3">
            <img src="{{ asset('images/icons/cuenta.png') }}"/>
            <span>Recibos</span>
        </div>
    </a>
</li>