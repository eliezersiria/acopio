<div>
    {{-- Be like water. --}}

    <div class="p-4">
        <p class="mb-2 text-sm text-gray-500">
            Tiempo de consulta: {{ $tiempo }} segundos | Registros cargados: {{ $numeroFilas }}
        </p>

        <p>
            <label class="label">Seleccione fecha</label>
        </p>

        <div class="flex items-center gap-2">
            <input type="date" class="input" wire:model.live="fechaReporte">
            <span class="loading loading-spinner" wire:loading wire:target="fechaReporte"></span>
        </div>

        <p>{{ $textoSemana }} </p>


        <div role="tablist" class="tabs tabs-border">
            <a role="tab" class="tab {{ $tipo_semana == 'A'? 'bg-primary font-bold text-white' : '' }}" wire:click="cambiarTipoSemana('A')">Grupo A</a>
            <a role="tab" class="tab {{ $tipo_semana == 'B'? 'bg-primary font-bold text-white' : '' }}" wire:click="cambiarTipoSemana('B')">Grupo B</a>
            <span class="loading loading-spinner" wire:loading wire:target="cambiarTipoSemana"></span>
        </div>

        <div role="tablist" class="tabs tabs-box">
            @foreach ($comarcas as $comarca)
            <a role="tab" class="tab transition{{ $comarca->id == $localidad_id? 'tab-active font-bold text-white bg-lime-700' : '' }}"
                wire:navigate wire:click="cambiarComarca({{ $comarca->id }})">
                {{ $comarca->nombre }}
            </a>
            @endforeach
            <span class="loading loading-spinner" wire:loading wire:target="cambiarComarca"></span>
        </div>
    </div>

</div>