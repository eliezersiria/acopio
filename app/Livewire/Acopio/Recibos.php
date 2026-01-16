<?php

namespace App\Livewire\Acopio;

use Livewire\Component;
use App\Models\Localidad;
use Illuminate\Support\Carbon;
use Carbon\CarbonInterface;
use App\Models\Productor;

class Recibos extends Component
{
    public $comarcas;
    public $localidad_id;
    public $localidad;
    public $dias = [];
    public $fechaReporte;
    public $tipo_semana;
    public $tiempo;
    public $numeroFilas;

    public function mount()
    {
        Carbon::setLocale('es');

        $this->comarcas = Localidad::all();

        // Localidad por defecto SOLO si no viene en URL
        $this->localidad_id ??= $this->comarcas->first()?->id;
        $this->localidad = Localidad::find($this->localidad_id)?->nombre;

        // Tipo de semana SOLO si no viene en URL
        $this->tipo_semana ??= 'A';

        // Fecha SOLO si no viene en URL
        $this->fechaReporte ??= now()->format('Y-m-d');
    }

    public function cambiarComarca($comarca_id)
    {
        $this->localidad_id = $comarca_id;
        $this->localidad = Localidad::find($comarca_id)?->nombre;
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

    public function getReporteProperty()
    {
        $inicio = microtime(true);
        $fin = microtime(true);
        $this->tiempo = round($fin - $inicio, 3);

        Carbon::setLocale('es');
        // Determinar el primer día de la semana según tipo_semana
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
                break;
        }
        $fechaFinal = $fechaInicial->copy()->addDays(6);

        //Consulta de productores y acopios
        $productores = Productor::with([
            'localidad',
            'acopios' => function ($query) use ($fechaInicial, $fechaFinal) {
                $query->whereBetween('fecha', [
                    $fechaInicial->format('Y-m-d'),
                    $fechaFinal->format('Y-m-d')
                ]);
            },
            'preciosSemanales' => function ($query) use ($fechaInicial) {
                $query->where('fecha_inicio', $fechaInicial);
            },
        ])
            ->withSum([
                'adelantos as total_efectivo' => function ($query) use ($fechaInicial, $fechaFinal) {
                    $query->whereBetween('fecha', [
                        $fechaInicial->format('Y-m-d'),
                        $fechaFinal->format('Y-m-d')
                    ]);
                }
            ], 'efectivo')
            ->withSum([
                'adelantos as total_alimentos' => function ($query) use ($fechaInicial, $fechaFinal) {
                    $query->whereBetween('fecha', [
                        $fechaInicial->format('Y-m-d'),
                        $fechaFinal->format('Y-m-d')
                    ]);
                }
            ], 'alimentos')
            ->withSum([
                'adelantos as total_lacteos' => function ($query) use ($fechaInicial, $fechaFinal) {
                    $query->whereBetween('fecha', [
                        $fechaInicial->format('Y-m-d'),
                        $fechaFinal->format('Y-m-d')
                    ]);
                }
            ], 'lacteos')
            ->withSum([
                'adelantos as total_otros' => function ($query) use ($fechaInicial, $fechaFinal) {
                    $query->whereBetween('fecha', [
                        $fechaInicial->format('Y-m-d'),
                        $fechaFinal->format('Y-m-d')
                    ]);
                }
            ], 'otros')
            ->withSum([
                'adelantos as total_combustible' => function ($query) use ($fechaInicial, $fechaFinal) {
                    $query->whereBetween('fecha', [
                        $fechaInicial->format('Y-m-d'),
                        $fechaFinal->format('Y-m-d')
                    ]);
                }
            ], 'combustible')
            ->where('semana', $this->tipo_semana)
            ->whereHas('localidad', function ($q) {
                $q->where('id', $this->localidad_id);
            })
            ->get();


    }

    public function render()
    {
        return view('livewire.acopio.recibos', [
            'reporte' => $this->reporte,
            'textoSemana' => $this->textoSemana
        ]);
    }
}
