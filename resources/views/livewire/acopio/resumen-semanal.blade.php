<div>
    <div class="p-4">

        <p class="mb-2 text-sm text-gray-500">
            Tiempo de consulta: {{ $tiempo }} segundos | Registros cargados: {{ $numeroFilas }}
        </p>

        <p>
            <label class="label">Seleccionar fecha</label>
        </p>
        <div class="flex items-center gap-2">
            <input type="date" class="input" wire:model.live="fechaReporte">
            <span class="loading loading-spinner" wire:loading wire:target="fechaReporte"></span>
        </div>

        <p>Resumen de {{ $textoSemana  }} </p>

        <div role="tablist" class="tabs tabs-border">
            <a role="tab" class="tab {{ $tipo_semana == 'A'? 'bg-primary font-bold text-white' : '' }}" wire:click="cambiarTipoSemana('A')">Grupo A</a>
            <a role="tab" class="tab {{ $tipo_semana == 'B'? 'bg-primary font-bold text-white' : '' }}" wire:click="cambiarTipoSemana('B')">Grupo B</a>
            <span class="loading loading-spinner" wire:loading wire:target="cambiarTipoSemana"></span>
        </div>

    </div>


    <div class="overflow-x-auto">
        <table class="table min-w-full border-collapse">
            <thead>
                <tr>
                    <th class="border border-gray-300 sticky left-0 bg-cyan-800 text-white z-10">
                        Comarca
                    </th>

                    @foreach ($dias as $dia)
                    <th class="border border-gray-300 bg-lime-700 text-white text-center">
                        {{ \Carbon\Carbon::parse($dia)->translatedFormat('D d') }}
                    </th>
                    @endforeach

                    <th class="bg-lime-800 text-white border border-gray-300">Total entregados</th>
                    <th class="bg-yellow-700 text-white border border-gray-300">Total C$</th>
                    <th class="bg-red-900 text-white border border-gray-300">% Deducción</th>

                    <th class="bg-cyan-800 text-white border border-gray-300">Efectivo</th>
                    <th class="bg-cyan-800 text-white border border-gray-300">Comb.</th>
                    <th class="bg-cyan-800 text-white border border-gray-300">Alim.</th>
                    <th class="bg-cyan-800 text-white border border-gray-300">Láct.</th>
                    <th class="bg-cyan-800 text-white border border-gray-300">Otros</th>

                    <th class="bg-red-900 text-white border border-gray-300">Total Deducciones</th>
                    <th class="bg-green-900 text-white border border-gray-300">Neto a Recibir</th>
                </tr>
            </thead>

            <tbody>
                @php
                $tgLitros = 0;
                $tgCordobas = 0;
                $tgDeduccion = 0;
                $tgEfectivo = 0;
                $tgCombustible = 0;
                $tgAlimentos = 0;
                $tgLacteos = 0;
                $tgOtros = 0;
                $tgTotalDeducciones = 0;
                $tgNeto = 0;
                @endphp

                @foreach ($reporteLocalidades as $localidad)
                <tr>
                    <td class="border border-gray-300 bg-gray-600 text-white sticky left-0 z-10 whitespace-nowrap">
                        {{ $localidad['localidad'] }}
                    </td>

                    @foreach ($dias as $dia)
                    <td class="border border-gray-300 text-center">
                        {{ number_format($localidad['litros_por_dia'][$dia] ?? 0, 0) }}
                    </td>
                    @endforeach

                    <td class="border border-gray-300 text-center">
                        {{ number_format($localidad['total_litros'], 0) }}
                    </td>

                    <td class="border border-gray-300 text-center">
                        {{ number_format($localidad['total_cordobas'], 0) }}
                    </td>

                    <td class="border border-gray-300 text-center">
                        {{ number_format($localidad['deduccion_compra'], 0) }}
                    </td>

                    <td class="border border-gray-300 text-center">
                        {{ number_format($localidad['total_efectivo'], 0) }}
                    </td>

                    <td class="border border-gray-300 text-center">
                        {{ number_format($localidad['total_combustible'], 0) }}
                    </td>

                    <td class="border border-gray-300 text-center">
                        {{ number_format($localidad['total_alimentos'], 0) }}
                    </td>

                    <td class="border border-gray-300 text-center">
                        {{ number_format($localidad['total_lacteos'], 0) }}
                    </td>

                    <td class="border border-gray-300 text-center">
                        {{ number_format($localidad['total_otros'], 0) }}
                    </td>

                    <td class="border border-gray-300 text-center">
                        {{ number_format($localidad['total_deducciones'], 0) }}
                    </td>

                    <td class="border border-gray-300 text-center">
                        {{ number_format($localidad['neto_recibir'], 0) }}
                    </td>
                </tr>

                @php
                $tgLitros += $localidad['total_litros'];
                $tgCordobas += $localidad['total_cordobas'];
                $tgDeduccion += $localidad['deduccion_compra'];
                $tgEfectivo += $localidad['total_efectivo'];
                $tgCombustible += $localidad['total_combustible'];
                $tgAlimentos += $localidad['total_alimentos'];
                $tgLacteos += $localidad['total_lacteos'];
                $tgOtros += $localidad['total_otros'];
                $tgTotalDeducciones += $localidad['total_deducciones'];
                $tgNeto += $localidad['neto_recibir'];
                @endphp
                @endforeach
            </tbody>

            <foot>
                <tr class="bg-gray-900">
                    <td class="bg-gray-800 text-xs text-white border border-gray-300 sticky left-0 whitespace-nowrap">
                        Total Recibido en Campo
                    </td>

                    @foreach ($dias as $dia)
                    <td class="border border-gray-300 text-center text-white">
                        {{ number_format($totalesCampoPorDia[$dia] ?? 0, 0) }}
                    </td>
                    @endforeach

                    <td class="border border-gray-300 text-center text-white">{{ number_format($tgLitros, 0) }}</td>
                    <td class="border border-gray-300 text-center text-white">{{ number_format($tgCordobas, 0) }}</td>
                    <td class="border border-gray-300 text-center text-white">{{ number_format($tgDeduccion, 0) }}</td>
                    <td class="border border-gray-300 text-center text-white">{{ number_format($tgEfectivo, 0) }}</td>
                    <td class="border border-gray-300 text-center text-white">{{ number_format($tgCombustible, 0) }}</td>
                    <td class="border border-gray-300 text-center text-white">{{ number_format($tgAlimentos, 0) }}</td>
                    <td class="border border-gray-300 text-center text-white">{{ number_format($tgLacteos, 0) }}</td>
                    <td class="border border-gray-300 text-center text-white">{{ number_format($tgOtros, 0) }}</td>
                    <td class="border border-gray-300 text-center text-white">{{ number_format($tgTotalDeducciones, 0) }}</td>
                    <td class="border border-gray-300 text-center text-white">{{ number_format($tgNeto, 0) }}</td>
                </tr>

                <tr>
                    <td class="bg-gray-800 text-white border border-gray-300 sticky left-0 whitespace-nowrap text-xs">
                        Total Recibido en Acopio
                    </td>

                    @foreach ($dias as $dia)
                    <td class="border border-gray-300 text-white text-center">
                        {{ number_format($totalAcopioPorDia[$dia] ?? 0, 0) }}
                    </td>
                    @endforeach

                    <td class="border border-gray-300 text-white text-center">
                        {{ number_format($this->totalAcopioSemana) }}
                    </td>
                </tr>

                <tr>
                    <td class="bg-gray-800 text-white border border-gray-300 sticky left-0 whitespace-nowrap text-xs">
                        Litros Perdidos en Ruta
                    </td>

                    @foreach ($dias as $dia)
                    <td class="border border-gray-300 bg-gray-200 text-center">
                        <span class="text-red-500 font-bold">
                            {{ number_format($litrosPerdidosPorDia[$dia] ?? 0, 0) }}
                        </span>
                    </td>
                    @endforeach

                    <td class="border border-gray-300 bg-gray-200 text-center">
                        <span class="text-red-500 font-bold">
                            {{ number_format($this->totalLitrosPerdidosSemana) }}
                        </span>
                    </td>

                </tr>

                <tr>
                    <td class="bg-gray-800 text-white border border-gray-300 sticky left-0 whitespace-nowrap text-xs">
                        % de litros Perdidos
                    </td>

                    @foreach ($dias as $dia)
                    <td class="border border-gray-300 text-center bg-gray-200 whitespace-nowrap">
                        <span class="text-red-600 font-bold">
                            {{ number_format($porcentajePerdidosPorDia[$dia], 2) }} %
                        </span>
                    </td>
                    @endforeach

                    <td class="border border-gray-300 text-center bg-gray-200">
                        <span class="text-red-600 font-bold">
                            {{ number_format($this->porcentajePerdidoSemana, 2) }}%
                        </span>
                    </td>

                </tr>
            </foot>
        </table>
    </div>
</div>