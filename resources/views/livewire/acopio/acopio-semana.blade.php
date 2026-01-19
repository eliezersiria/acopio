<div>
    {{-- The best athlete wants his opponent at his best. --}}

    <div class="p-4">
        <p class="mb-2 text-sm text-gray-500">
            Tiempo de consulta: {{ $tiempo }} segundos | Registros cargados: {{ $numeroFilas }}
        </p>

        <p>
            <label class="label">Seleccione fecha</label>
        </p>
        <div class="flex items-center gap-2">
            <input type="date" class="input" wire:model.lazy="fechaReporte">
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

    <div class="overflow-x-auto">

        <table class="table min-w-full border-collapse">
            <thead>
                <tr>
                    <th class="bg-cyan-800 text-white sticky left-0 z-10 border border-gray-300 text-left text-sm">
                        Nombres
                    </th>

                    @foreach ($dias as $dia)
                    <th class="border border-gray-300 text-center text-sm bg-lime-700 text-white">
                        {{ ucfirst($dia->translatedFormat('D d')) }}
                    </th>
                    @endforeach

                    <th class="border border-gray-300 text-left text-sm bg-lime-800 text-white">
                        Total entregados
                    </th>

                    <th class="border border-gray-300 py-3 text-sm bg-yellow-700 text-white">
                        Precio litro
                    </th>

                    <th class="border border-gray-300 text-sm bg-lime-800 text-white">
                        Total córdobas
                    </th>

                    <th class="border border-gray-300 text-sm bg-red-900 text-white">
                        % Deducción compra
                    </th>

                    <th class="bg-cyan-800 text-white border border-gray-300 text-sm">
                        Efectivo
                    </th>

                    <th class="bg-cyan-800 text-white border border-gray-300 text-sm">
                        Combustible
                    </th>

                    <th class="bg-cyan-800 text-white border border-gray-300 text-sm">
                        Alimentos
                    </th>

                    <th class="bg-cyan-800 text-white border border-gray-300 text-sm">
                        Lácteos
                    </th>

                    <th class="bg-cyan-800 text-white border border-gray-300 text-sm">
                        Otros
                    </th>
                    <th class="border border-gray-300 text-sm bg-red-900 text-white">
                        Deducciones
                    </th>
                    <th class="border border-gray-300 text-sm bg-green-900 text-white">
                        Neto a recibir
                    </th>
                </tr>
            </thead>
            <tbody>
                @foreach ($reporte as $fila)
                <tr>
                    <td class="bg-gray-600 text-white sticky left-0 z-10 border border-gray-300 text-sm whitespace-nowrap">
                        {{ $fila['productor'] }}
                    </td>
                    {{-- ------------------------------------------------------------------------------------------------------- --}}
                    @foreach ($dias as $dia)
                    @php $fecha = $dia->format('Y-m-d'); @endphp
                    <td class="border border-gray-300 text-sm text-center align-middle hover:bg-amber-500 hover:cursor-pointer hover:text-black"
                        wire:click="editar({{ $fila['productor_id'] }}, '{{ $fecha }}', '{{ $fila['localidad_id'] }}')">
                        @if($editando &&
                        $editando['productor_id'] == $fila['productor_id'] &&
                        $editando['fecha'] == $fecha)

                        <div x-data class="w-full h-full" @click.outside="$wire.guardar()">

                            <input type="number"
                                class="w-full h-full border-0 text-center text-sm focus:outline-none focus:ring-0 hover:bg-base-300"
                                wire:model.defer="litros"
                                wire:keydown.enter="guardar"
                                wire:blur="guardar"
                                wire:keydown.escape="$set('editando', null)"
                                name="litros"
                                x-ref="input"
                                x-init="$nextTick(() => { $refs.input.focus(); $refs.input.select() })">

                        </div>
                        @else
                        {{ $fila['litros'][$fecha] ?? '' }}
                        @endif

                    </td>
                    @endforeach
                    {{-- ------------------------------------------------------------------------------------------------------- --}}
                    <td class="border border-gray-300 text-sm text-center">
                        {{ $fila['total_litros'] ?? '0' }}
                    </td>
                    {{-- ------------------------------------------------------------------------------------------------------- --}}
                    <td class="border border-gray-300 text-sm text-center hover:bg-amber-500 hover:text-black hover:cursor-pointer"
                        wire:click="editar_precio_semanal({{ $fila['productor_id'] }}, '{{ $fila['fecha_inicial'] }}', '{{ $fila['localidad_id'] }}')">
                        @if($editando_precio_litro &&
                        $editando_precio_litro['productor_id'] == $fila['productor_id'] &&
                        $editando_precio_litro['fecha_inicio'] == $fila['fecha_inicial'])

                        <div x-data class="w-full h-full" @click.outside="$wire.guardar_precio_semanal_litro()">

                            <input type="number"
                                class="w-full h-full border-0 text-center text-sm focus:outline-none focus:ring-0 hover:bg-base-300"
                                wire:model.defer="precio_leche_semanal"
                                wire:keydown.enter="guardar_precio_semanal_litro"
                                wire:blur="guardar_precio_semanal_litro"
                                wire:keydown.escape="$set('editando_precio_litro', null)"
                                name="precio_leche_semanal"
                                x-ref="input_precio_leche"
                                x-init="$nextTick(() => { $refs.input_precio_leche.focus(); $refs.input_precio_leche.select() })">

                        </div>
                        @else
                        {{ intval($fila['precio_semanal']) ?? '0' }}
                        @endif

                    </td>
                    {{-- ------------------------------------------------------------------------------------------------------- --}}
                    <td class="border border-gray-300 text-sm text-center">
                        {{ number_format($fila['total_cordobas']) ?? '0' }}
                    </td>
                    <td class="border border-gray-300 text-sm text-center">
                        {{ number_format($fila['deduccion_compra']) ?? '0' }}
                    </td>
                    {{-- ------------------------------------------------------------------------------------------------------- --}}
                    <td class="border border-gray-300 text-sm text-center hover:bg-amber-500 hover:text-black hover:cursor-pointer"
                        wire:click="editar_adelantos({{ $fila['productor_id'] }},'{{ $fila['fecha_inicial'] }}','efectivo')">
                        @if($editando_adelantos &&
                        $editando_adelantos['productor_id'] == $fila['productor_id'] &&
                        $editando_adelantos['fecha'] == $fila['fecha_inicial'] &&
                        $editando_adelantos['campo'] === 'efectivo')

                        <div x-data class="w-full h-full" @click.outside="$wire.guardar_adelantos()">

                            <input type="number"
                                class="w-full h-full border-0 text-center text-sm focus:outline-none focus:ring-0 hover:bg-base-300"
                                wire:model.defer="cantidad"
                                wire:keydown.enter="guardar_adelantos"
                                wire:blur="guardar_adelantos"
                                wire:keydown.escape="$set('guardar_adelantos', null)"
                                x-ref="input_cantidad"
                                x-init="$nextTick(() => { $refs.input_cantidad.focus(); $refs.input_cantidad.select() })">
                        </div>
                        @else
                        {{ number_format($fila['total_efectivo']) ?? '0' }}
                        @endif
                    </td>
                    {{-- ------------------------------------------------------------------------------------------------------- --}}
                    <td class="border border-gray-300 text-sm text-center hover:bg-amber-500 hover:text-black hover:cursor-pointer"
                        wire:click="editar_adelantos({{ $fila['productor_id'] }},'{{ $fila['fecha_inicial'] }}','combustible')">

                        @if($editando_adelantos &&
                        $editando_adelantos['productor_id'] == $fila['productor_id'] &&
                        $editando_adelantos['fecha'] == $fila['fecha_inicial']&&
                        $editando_adelantos['campo'] === 'combustible')

                        <div x-data class="w-full h-full" @click.outside="$wire.guardar_adelantos()">

                            <input type="number"
                                class="w-full h-full border-0 text-center text-sm focus:outline-none focus:ring-0 hover:bg-base-300"
                                wire:model.defer="cantidad"
                                wire:keydown.enter="guardar_adelantos"
                                wire:blur="guardar_adelantos"
                                wire:keydown.escape="$set('guardar_adelantos', null)"
                                x-ref="input_cantidad"
                                x-init="$nextTick(() => { $refs.input_cantidad.focus(); $refs.input_cantidad.select() })">
                        </div>
                        @else
                        {{ number_format($fila['total_combustible']) ?? '0' }}
                        @endif
                    </td>
                    {{-- ------------------------------------------------------------------------------------------------------- --}}
                    <td class="border border-gray-300 text-sm text-center hover:bg-amber-500 hover:text-black hover:cursor-pointer"
                        wire:click="editar_adelantos({{ $fila['productor_id'] }},'{{ $fila['fecha_inicial'] }}','alimentos')">

                        @if($editando_adelantos &&
                        $editando_adelantos['productor_id'] == $fila['productor_id'] &&
                        $editando_adelantos['fecha'] == $fila['fecha_inicial']&&
                        $editando_adelantos['campo'] === 'alimentos')

                        <div x-data class="w-full h-full" @click.outside="$wire.guardar_adelantos()">

                            <input type="number"
                                class="w-full h-full border-0 text-center text-sm focus:outline-none focus:ring-0 hover:bg-base-300"
                                wire:model.defer="cantidad"
                                wire:keydown.enter="guardar_adelantos"
                                wire:blur="guardar_adelantos"
                                wire:keydown.escape="$set('guardar_adelantos', null)"
                                x-ref="input_cantidad"
                                x-init="$nextTick(() => { $refs.input_cantidad.focus(); $refs.input_cantidad.select() })">
                        </div>
                        @else
                        {{ number_format($fila['total_alimentos']) ?? '0' }}
                        @endif
                    </td>
                    {{-- ------------------------------------------------------------------------------------------------------- --}}
                    <td class="border border-gray-300 text-sm text-center hover:bg-amber-500 hover:text-black hover:cursor-pointer"
                        wire:click="editar_adelantos({{ $fila['productor_id'] }},'{{ $fila['fecha_inicial'] }}','lacteos')">

                        @if($editando_adelantos &&
                        $editando_adelantos['productor_id'] == $fila['productor_id'] &&
                        $editando_adelantos['fecha'] == $fila['fecha_inicial']&&
                        $editando_adelantos['campo'] === 'lacteos')

                        <div x-data class="w-full h-full" @click.outside="$wire.guardar_adelantos()">

                            <input type="number"
                                class="w-full h-full border-0 text-center text-sm focus:outline-none focus:ring-0 hover:bg-base-300"
                                wire:model.defer="cantidad"
                                wire:keydown.enter="guardar_adelantos"
                                wire:blur="guardar_adelantos"
                                wire:keydown.escape="$set('guardar_adelantos', null)"
                                x-ref="input_cantidad"
                                x-init="$nextTick(() => { $refs.input_cantidad.focus(); $refs.input_cantidad.select() })">
                        </div>
                        @else
                        {{ number_format($fila['total_lacteos']) ?? '0' }}
                        @endif
                    </td>

                    {{-- ------------------------------------------------------------------------------------------------------- --}}
                    <td class="border border-gray-300 text-sm text-center hover:bg-amber-500 hover:text-black hover:cursor-pointer"
                        wire:click="editar_adelantos({{ $fila['productor_id'] }},'{{ $fila['fecha_inicial'] }}','otros')">

                        @if($editando_adelantos &&
                        $editando_adelantos['productor_id'] == $fila['productor_id'] &&
                        $editando_adelantos['fecha'] == $fila['fecha_inicial']&&
                        $editando_adelantos['campo'] === 'otros')

                        <div x-data class="w-full h-full" @click.outside="$wire.guardar_adelantos()">

                            <input type="number"
                                class="w-full h-full border-0 text-center text-sm focus:outline-none focus:ring-0 hover:bg-base-300"
                                wire:model.defer="cantidad"
                                wire:keydown.enter="guardar_adelantos"
                                wire:blur="guardar_adelantos"
                                wire:keydown.escape="$set('guardar_adelantos', null)"
                                x-ref="input_cantidad"
                                x-init="$nextTick(() => { $refs.input_cantidad.focus(); $refs.input_cantidad.select() })">
                        </div>
                        @else
                        {{ number_format($fila['total_otros']) ?? '0' }}
                        @endif
                    </td>


                    <td class="border border-gray-300 text-sm text-center">
                        {{ number_format($fila['total_deducciones']) ?? '' }}
                    </td>
                    <td class="border border-gray-300 text-sm text-center">
                        {{ number_format($fila['neto_recibir']) ?? '' }}
                    </td>
                </tr>
                @endforeach

                <tr style="font-family: 'Ubuntu', sans-serif;">
                    <td class="bg-gray-800 text-white sticky left-0 z-10 border border-gray-300 text-left text-xs whitespace-nowrap">
                        Total Recibido en Campo
                    </td>
                    @foreach ($dias as $dia)
                    @php $fecha = $dia->format('Y-m-d'); @endphp
                    <td class="border border-gray-300 text-center">
                        {{ number_format($totalesPorDia[$fecha] ?? 0) }}
                    </td>
                    @endforeach
                    <td class="border border-gray-300 text-center">
                        {{ number_format(array_sum($totalesPorDia)) }}
                    </td>

                    <td class="border border-gray-300 text-center">
                        -
                    </td>

                    {{-- Total córdobas --}}
                    <td class="border border-gray-300 text-center">
                        {{ number_format($totalCordobas) }}
                    </td>

                    <td class="border border-gray-300 text-center">
                        {{ number_format($totalDeduccionCompra, 0) }}
                    </td>

                    <td class="border border-gray-300 text-center">
                        {{ number_format($totalEfectivo, 0) }}
                    </td>

                    <td class="border border-gray-300 text-center">
                        {{ number_format($totalCombustible, 0) }}
                    </td>

                    <td class="border border-gray-300 text-center">
                        {{ number_format($totalAlimentos, 0) }}
                    </td>

                    <td class="border border-gray-300 text-center">
                        {{ number_format($totalLacteos, 0) }}
                    </td>

                    <td class="border border-gray-300 text-center">
                        {{ number_format($totalOtros, 0) }}
                    </td>

                    <td class="border border-gray-300 text-center">
                        {{ number_format($totalDeducciones, 1) }}
                    </td>

                    <td class="border border-gray-300 text-center">
                        {{ number_format($totalNetoRecibir, 1) }}
                    </td>
                </tr>

                <tr>
                    <td class="bg-gray-800 text-white sticky left-0 z-10 border border-gray-300 text-left text-xs whitespace-nowrap">
                        Total Recibido en Acopio
                    </td>

                    @foreach ($dias as $dia)
                    @php $fecha = $dia->format('Y-m-d'); @endphp
                    <td class="border border-gray-300 text-center cursor-pointer hover:bg-amber-500" wire:click="editarAcopio('{{ $fecha }}')">

                        @if($editandoAcopio &&
                        $editandoAcopio['fecha'] === $fecha &&
                        $editandoAcopio['localidad_id'] === $localidad_id &&
                        $editandoAcopio['tipo_semana'] === $tipo_semana)

                        <input type="number" class="w-full text-center"
                            wire:model.defer="litrosAcopio"
                            wire:keydown.enter="guardarAcopio('{{ $fecha }}')"
                            wire:blur="guardarAcopio('{{ $fecha }}')"
                            wire:keydown.escape="$set('editandoAcopio', null)"
                            x-data
                            x-init="$nextTick(() => { $el.focus(); $el.select() })">

                        @else
                        {{ number_format($totalesAcopio[$fecha] ?? 0) }}
                        @endif
                    </td>
                    @endforeach

                    <td class="border border-gray-300 text-center">
                       {{ number_format($totalRecibidoAcopio) }}
                    </td>
                </tr>

                <tr>
                    <td class="bg-gray-800 text-white sticky left-0 z-10 border border-gray-300 text-left text-xs whitespace-nowrap">
                        Litros Perdidos en Ruta
                    </td>
                    @foreach ($dias as $dia)
                    @php $fecha = $dia->format('Y-m-d'); @endphp
                    <td class="bg-red-900 text-white border border-gray-300 text-center">
                        {{ number_format(($totalesPorDia[$fecha] ?? 0) - ($totalesAcopio[$fecha] ?? 0)) }}
                    </td>
                    @endforeach
                    <td class="bg-red-900 text-white border border-gray-300 text-center">
                        {{ number_format(array_sum($totalesPorDia) - $totalRecibidoAcopio) }}
                    </td>
                </tr>

                <tr>
                    <td class="bg-red-900 text-white sticky left-0 z-10 border border-gray-300 text-left text-xs whitespace-nowrap">
                        % Litros perdidos
                    </td>

                    @foreach ($dias as $dia)
                    @php $fecha = $dia->format('Y-m-d'); @endphp
                    @php
                    $campo = $totalesPorDia[$fecha] ?? 0;
                    $acopio = $totalesAcopio[$fecha] ?? 0;
                    $porcentaje = $campo > 0 ? (($campo - $acopio) / $campo * 100) : 0;
                    @endphp
                    <td class="bg-amber-800 text-white border border-gray-300 text-center">
                        {{ number_format($porcentaje, 2) }}%
                    </td>
                    @endforeach

                    <td class="bg-amber-800 text-white border border-gray-300 text-center">
                        
                    </td>

                </tr>


            </tbody>
        </table>

    </div>

</div>











</div>