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

        <p>{{ $this->textoSemana }} </p>


        <div role="tablist" class="tabs tabs-border">
            <a role="tab" class="tab {{ $tipo_semana == 'A'? 'bg-primary font-bold text-white' : '' }}" wire:click="cambiarTipoSemana('A')">Grupo A</a>
            <a role="tab" class="tab {{ $tipo_semana == 'B'? 'bg-primary font-bold text-white' : '' }}" wire:click="cambiarTipoSemana('B')">Grupo B</a>
            <span class="loading loading-spinner" wire:loading wire:target="cambiarTipoSemana"></span>
        </div>

        <div role="tablist" class="tabs tabs-box">
            @foreach ($comarcas as $comarca)
            <a role="tab" class="tab transition{{ $comarca->id == $localidad_id? 'tab-active font-bold text-white bg-lime-700' : '' }}"
                wire:click="cambiarComarca({{ $comarca->id }})">
                {{ $comarca->nombre }}
            </a>
            @endforeach
            <span class="loading loading-spinner" wire:loading wire:target="cambiarComarca"></span>
        </div>

        <a href="#" class="btn btn-xs btn-info">Generar PDF</a>
    </div>


    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3">

        @foreach ($productores as $productor)
        @php
        $totalLitrosProductor = 0;
        $totalCordobasProductor = 0;
        $deduccionCompra = 0;
        $netoRecibir = 0;
        @endphp
        <div class="card bg-base-100">
            <div class="card-body">
                <h2 class="card-title">{{ $this->textoSemana }}</h2>
                <h2 class="card-title">{{ $productor->nombre }}</h2>

                <div class="overflow-x-auto">
                    <table class="table table-zebra border">
                        <thead>
                            <tr class="bg-emerald-400 text-black border">
                                <th>Día</th>
                                <th>Litros</th>
                                <th>Total C$</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($dias as $dia)
                            @php

                            $acopio = $productor->acopios->firstWhere('fecha', $dia->toDateString());

                            $litros = $acopio->litros ?? 0;

                            $precio = \App\Models\PrecioLecheSemanal::where('productor_id', $productor->id)
                            ->where('fecha_inicio', $fechaInicial)
                            ->orderByDesc('fecha_inicio')
                            ->value('precio') ?? 0;

                            $total = $litros * $precio;
                            // 👇 acumuladores
                            $totalLitrosProductor += $litros;
                            $totalCordobasProductor += $total;
                            $deduccionCompra = $totalCordobasProductor * 0.013;
                            $netoRecibir = $totalCordobasProductor - $deduccionCompra - $productor->adelantos->sum('total');
                            @endphp

                            <tr class="hover:bg-base-300">
                                <td class="py-2">{{ $dia->locale('es')->isoFormat('ddd DD') }}</td>
                                <td class="py-2 text-center">{{ number_format($litros) }}</td>
                                <td class="py-2 text-right">
                                    C$ {{ number_format($total, 2) }}
                                </td>
                            </tr>
                            @endforeach

                            <tr class="font-bold">
                                <td>Total</td>
                                <td class="text-center">{{ $totalLitrosProductor }}</td>
                                <td class="text-right">
                                    C$ {{ number_format($totalCordobasProductor, 2) }}
                                </td>
                            </tr>

                            <tr>
                                <td colspan="3" class="text-center font-bold">Deducciones</td>
                            </tr>

                            <tr>
                                <td colspan="2" class="text-xs">% de Deducción por compra</td>
                                <td class="text-right font-bold">C$ {{ number_format($deduccionCompra) }}</td>
                            </tr>

                            <tr>
                                <td colspan="2" class="text-xs">Anticipos y/o Adelantos</td>
                                <td class="text-right font-bold">C$ {{ $productor->adelantos->sum('total') }}</td>
                            </tr>

                            <tr>
                                <td colspan="2" class="text-xs">Neto a Recibir</td>
                                <td class="text-right font-bold">C$ {{ number_format($netoRecibir, 2) }}</td>
                            </tr>
                        </tbody>

                    </table>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    {{-- 🔽 Enlaces de paginación --}}
    <div class="mt-4 w-1/2">
        {{ $productores->links() }}
    </div>





</div>