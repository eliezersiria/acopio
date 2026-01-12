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
                        Localidad
                    </th>

                    @foreach ($dias as $dia)
                    <th class="border border-gray-300 bg-lime-700 text-white text-center">
                        {{ \Carbon\Carbon::parse($dia)->translatedFormat('D d') }}
                    </th>
                    @endforeach

                    <th class="border border-gray-300">Total Litros</th>
                    <th class="border border-gray-300">Total C$</th>
                    <th class="border border-gray-300">% Deducción</th>

                    <th class="border border-gray-300">Efectivo</th>
                    <th class="border border-gray-300">Comb.</th>
                    <th class="border border-gray-300">Alim.</th>
                    <th class="border border-gray-300">Láct.</th>
                    <th class="border border-gray-300">Otros</th>

                    <th class="border border-gray-300">Total Deducciones</th>
                    <th class="border border-gray-300">Neto a Recibir</th>
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
                    <td class="border border-gray-300 text-right">
                        {{ number_format($localidad['litros_por_dia'][$dia] ?? 0, 0) }}
                    </td>
                    @endforeach

                    <td class="border border-gray-300 text-right font-semibold">
                        {{ number_format($localidad['total_litros'], 0) }}
                    </td>

                    <td class="border border-gray-300 text-right">
                        {{ number_format($localidad['total_cordobas'], 0) }}
                    </td>

                    <td class="border border-gray-300 text-right">
                        {{ number_format($localidad['deduccion_compra'], 0) }}
                    </td>

                    <td class="border border-gray-300 text-right">
                        {{ number_format($localidad['total_efectivo'], 0) }}
                    </td>

                    <td class="border border-gray-300 text-right">
                        {{ number_format($localidad['total_combustible'], 2) }}
                    </td>

                    <td class="border border-gray-300 text-right">
                        {{ number_format($localidad['total_alimentos'], 2) }}
                    </td>

                    <td class="border border-gray-300 text-right">
                        {{ number_format($localidad['total_lacteos'], 2) }}
                    </td>

                    <td class="border border-gray-300 text-right">
                        {{ number_format($localidad['total_otros'], 2) }}
                    </td>

                    <td class="border border-gray-300 text-right font-semibold">
                        {{ number_format($localidad['total_deducciones'], 2) }}
                    </td>

                    <td class="border border-gray-300 text-right font-bold">
                        {{ number_format($localidad['neto_recibir'], 2) }}
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

            <tfoot>
                <tr class="bg-gray-900 text-white font-bold">
                    <td class="border border-gray-300 sticky left-0 bg-gray-900">
                        TOTALES
                    </td>

                    @foreach ($dias as $dia)
                    <td></td>
                    @endforeach

                    <td class="text-right">{{ number_format($tgLitros, 2) }}</td>
                    <td class="text-right">{{ number_format($tgCordobas, 2) }}</td>
                    <td class="text-right">{{ number_format($tgDeduccion, 2) }}</td>
                    <td class="text-right">{{ number_format($tgEfectivo, 2) }}</td>
                    <td class="text-right">{{ number_format($tgCombustible, 2) }}</td>
                    <td class="text-right">{{ number_format($tgAlimentos, 2) }}</td>
                    <td class="text-right">{{ number_format($tgLacteos, 2) }}</td>
                    <td class="text-right">{{ number_format($tgOtros, 2) }}</td>
                    <td class="text-right">{{ number_format($tgTotalDeducciones, 2) }}</td>
                    <td class="text-right">{{ number_format($tgNeto, 2) }}</td>
                </tr>
            </tfoot>
        </table>
    </div>
</div>