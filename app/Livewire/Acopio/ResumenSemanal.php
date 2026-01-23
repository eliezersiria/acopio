<?php

namespace App\Livewire\Acopio;

use Livewire\Component;
use Carbon\Carbon;
use Carbon\CarbonInterface;
use App\Models\Acopio;
use App\Models\Adelanto;
use App\Models\PrecioLecheSemanal;
use App\Models\Productor;
use App\Models\totalDiarioAcopio;


class ResumenSemanal extends Component
{
    public $tipo_semana;
    public $dias = [];
    public $numeroFilas;
    public $tiempo;
    public $fechaReporte;
    public $totalesCampoPorDia  = [];
    public $totalAcopioPorDia = [];
    public $litrosPerdidosPorDia = [];
    public $porcentajePerdidosPorDia = [];

    protected $queryString = [
        //'localidad_id' => ['except' => null],
        'tipo_semana' => ['except' => null],
        'fechaReporte' => ['except' => null],
    ];

    public function mount()
    {
        Carbon::setLocale('es');

        // Fecha SOLO si no viene en URL
        $this->fechaReporte ??= now()->format('Y-m-d');
        $this->tipo_semana ??= 'A'; // 👈 O 'B'
    }

    public function cambiarTipoSemana($type_week)
    {
        $this->tipo_semana = $type_week;
    }

    public function getTextoSemanaProperty()
    {
        $hoy = Carbon::parse($this->fechaReporte);

        $fechaInicial = match ($this->tipo_semana) {
            'A' => $hoy->copy()->startOfWeek(CarbonInterface::SUNDAY),
            'B' => $hoy->copy()->startOfWeek(CarbonInterface::FRIDAY),
            default => $hoy->copy()->startOfWeek(CarbonInterface::SUNDAY),
        };

        $fechaFinal = $fechaInicial->copy()->addDays(6);

        // Generar los días de la semana
        $this->dias = [];
        for ($i = 0; $i < 7; $i++) {
            $this->dias[] = $fechaInicial->copy()->addDays($i)->format('Y-m-d');
        }

        if ($fechaInicial->format('m-Y') === $fechaFinal->format('m-Y')) {
            return 'Semana del ' . $fechaInicial->format('d')
                . ' al ' . $fechaFinal->format('d')
                . ' de ' . $fechaInicial->locale('es')->isoFormat('MMMM');
        } else {
            return 'Semana del ' . $fechaInicial->format('d') . ' '
                . $fechaInicial->locale('es')->isoFormat('MMMM')
                . ' al ' . $fechaFinal->format('d') . ' '
                . $fechaFinal->locale('es')->isoFormat('MMMM') . ' '
                . $fechaFinal->format('Y');
        }
    }
    public function getReporteLocalidadesProperty()
    {
        $inicio = microtime(true);
        Carbon::setLocale('es');

        // Determinar semana
        $hoy = Carbon::parse($this->fechaReporte);

        switch ($this->tipo_semana) {
            case 'A':
                $fechaInicial = $hoy->copy()->startOfWeek(CarbonInterface::SUNDAY);
                break;
            case 'B':
                $fechaInicial = $hoy->copy()->startOfWeek(CarbonInterface::FRIDAY);
                break;
            default:
                $fechaInicial = $hoy->copy()->startOfWeek(CarbonInterface::SUNDAY);
        }

        $fechaFinal = $fechaInicial->copy()->addDays(6);

        // Días de la semana
        $this->dias = [];
        for ($i = 0; $i < 7; $i++) {
            $this->dias[] = $fechaInicial->copy()->addDays($i)->format('Y-m-d');
        }

        // Inicializar totales generales
        $this->totalesCampoPorDia   = array_fill_keys($this->dias, 0);
        $this->totalAcopioPorDia = array_fill_keys($this->dias, 0);
        $this->litrosPerdidosPorDia = array_fill_keys($this->dias, 0);
        $this->porcentajePerdidosPorDia = array_fill_keys($this->dias, 0);

        // Productores con relaciones
        $productores = Productor::with([
            'localidad',
            'acopios' => function ($q) use ($fechaInicial, $fechaFinal) {
                $q->whereBetween('fecha', [
                    $fechaInicial->format('Y-m-d'),
                    $fechaFinal->format('Y-m-d')
                ]);
            },
            'preciosSemanales' => function ($q) use ($fechaInicial) {
                $q->where('fecha_inicio', $fechaInicial);
            },
        ])
            ->withSum([
                'adelantos as total_efectivo' => function ($q) use ($fechaInicial, $fechaFinal) {
                    $q->whereBetween('fecha', [
                        $fechaInicial->format('Y-m-d'),
                        $fechaFinal->format('Y-m-d')
                    ]);
                }
            ], 'efectivo')
            ->withSum([
                'adelantos as total_combustible' => function ($q) use ($fechaInicial, $fechaFinal) {
                    $q->whereBetween('fecha', [
                        $fechaInicial->format('Y-m-d'),
                        $fechaFinal->format('Y-m-d')
                    ]);
                }
            ], 'combustible')
            ->withSum([
                'adelantos as total_alimentos' => function ($q) use ($fechaInicial, $fechaFinal) {
                    $q->whereBetween('fecha', [
                        $fechaInicial->format('Y-m-d'),
                        $fechaFinal->format('Y-m-d')
                    ]);
                }
            ], 'alimentos')
            ->withSum([
                'adelantos as total_lacteos' => function ($q) use ($fechaInicial, $fechaFinal) {
                    $q->whereBetween('fecha', [
                        $fechaInicial->format('Y-m-d'),
                        $fechaFinal->format('Y-m-d')
                    ]);
                }
            ], 'lacteos')
            ->withSum([
                'adelantos as total_otros' => function ($q) use ($fechaInicial, $fechaFinal) {
                    $q->whereBetween('fecha', [
                        $fechaInicial->format('Y-m-d'),
                        $fechaFinal->format('Y-m-d')
                    ]);
                }
            ], 'otros')
            ->where('semana', $this->tipo_semana)
            ->get();


        //📌 Consulta por rango de fechas y tipo de semana
        $totalesAcopio = TotalDiarioAcopio::whereBetween('fecha', [
            $fechaInicial->format('Y-m-d'),
            $fechaFinal->format('Y-m-d')
        ])
            ->where('tipo_semana', $this->tipo_semana)
            ->select('fecha')
            ->selectRaw('SUM(litros) as total_litros')
            ->groupBy('fecha')
            ->get();

        //🔹 Cargar el total por día
        foreach ($totalesAcopio as $row) {
            $this->totalAcopioPorDia[$row->fecha] = (float) $row->total_litros;
        }

        $reporte = [];

        foreach ($productores as $productor) {

            $localidadId = $productor->localidad_id;
            $localidadNombre = $productor->localidad->nombre;

            if (!isset($reporte[$localidadId])) {
                $reporte[$localidadId] = [
                    'localidad_id' => $localidadId,
                    'localidad' => $localidadNombre,
                    'litros_por_dia' => array_fill_keys($this->dias, 0),
                    'total_litros' => 0,
                    'total_cordobas' => 0,
                    'deduccion_compra' => 0,
                    'total_efectivo' => 0,
                    'total_combustible' => 0,
                    'total_alimentos' => 0,
                    'total_lacteos' => 0,
                    'total_otros' => 0,
                    'total_deducciones' => 0,
                    'neto_recibir' => 0,
                ];
            }

            $precio = $productor->preciosSemanales->first()?->precio ?? 0;

            foreach ($productor->acopios as $acopio) {
                $fecha = $acopio->fecha;
                $litros = (float) $acopio->litros;

                $reporte[$localidadId]['litros_por_dia'][$fecha] += $litros;
                $reporte[$localidadId]['total_litros'] += $litros;
                $reporte[$localidadId]['total_cordobas'] += $litros * $precio;

                // 👉 TOTAL GENERAL POR DÍA
                $this->totalesCampoPorDia[$fecha] += $litros;
            }

            // Adelantos
            $reporte[$localidadId]['total_efectivo'] += $productor->total_efectivo;
            $reporte[$localidadId]['total_combustible'] += $productor->total_combustible;
            $reporte[$localidadId]['total_alimentos'] += $productor->total_alimentos;
            $reporte[$localidadId]['total_lacteos'] += $productor->total_lacteos;
            $reporte[$localidadId]['total_otros'] += $productor->total_otros;
        }

        //4️⃣ Calcular Litros Perdidos por día
        foreach ($this->dias as $dia) {
            $campo  = $this->totalesCampoPorDia[$dia] ?? 0;
            $acopio = $this->totalAcopioPorDia[$dia] ?? 0;

            $perdidos = max(0, $campo - $acopio);
            $this->litrosPerdidosPorDia[$dia] = $perdidos;

            $this->porcentajePerdidosPorDia[$dia] = $campo > 0
                ? round(($perdidos / $campo) * 100, 2)
                : 0;
        }


        // Cálculos finales por localidad
        foreach ($reporte as &$loc) {
            $loc['deduccion_compra'] = $loc['total_cordobas'] * 0.013;

            $loc['total_deducciones'] =
                $loc['deduccion_compra'] +
                $loc['total_efectivo'] +
                $loc['total_combustible'] +
                $loc['total_alimentos'] +
                $loc['total_lacteos'] +
                $loc['total_otros'];

            $loc['neto_recibir'] = $loc['total_cordobas'] - $loc['total_deducciones'];
        }

        $this->numeroFilas = count($reporte);
        $fin = microtime(true);
        $this->tiempo = round($fin - $inicio, 3);

        return $reporte;
    }


    public function render()
    {
        return view('livewire.acopio.resumen-semanal', [
            'reporteLocalidades' => $this->reporteLocalidades,
            'textoSemana' => $this->textoSemana,
        ]);
    }
}
