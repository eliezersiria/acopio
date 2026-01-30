<?php

namespace App\Livewire\Acopio;

use Livewire\Component;
use App\Models\Localidad;
use Illuminate\Support\Carbon;
use Carbon\CarbonInterface;
use App\Models\Productor;
use Livewire\WithPagination;
use Illuminate\Support\Facades\DB;

class Recibos extends Component
{
    use WithPagination;

    public $comarcas;
    public $localidad_id;
    public $localidad;
    public $dias = [];
    public $fechaReporte;
    public $tipo_semana;
    public $tiempo;
    public $numeroFilas;

    protected $queryString = [
        'localidad_id' => ['except' => null],
        'tipo_semana' => ['except' => null],
        'fechaReporte' => ['except' => null],
    ];

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

    // Quitamos cargarReporte de mount y lo llamamos desde render

    public function cambiarComarca($comarca_id)
    {
        $this->localidad_id = $comarca_id;
        $this->localidad = Localidad::find($comarca_id)?->nombre;
        $this->resetPage(); // Importante: resetear paginación
    }

    public function cambiarTipoSemana($type_week)
    {
        $this->tipo_semana = $type_week;
        $this->resetPage(); // Importante: resetear paginación
    }

    public function updatedFechaReporte()
    {
        $this->resetPage(); // Importante: resetear paginación
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
                . ' de ' . $fechaInicial->locale('es')->isoFormat('MMMM') . ' '
                . $fechaFinal->format('Y');
        } else {
            return 'Semana del ' . $fechaInicial->format('d') . ' '
                . $fechaInicial->locale('es')->isoFormat('MMMM')
                . ' al ' . $fechaFinal->format('d') . ' '
                . $fechaFinal->locale('es')->isoFormat('MMMM') . ' '
                . $fechaFinal->format('Y');
        }
    }

    public function render()
    {
        $inicio = microtime(true);
        Carbon::setLocale('es');

        $hoy = Carbon::parse($this->fechaReporte);

        $fechaInicial = match ($this->tipo_semana) {
            'A' => $hoy->copy()->startOfWeek(CarbonInterface::SUNDAY),
            'B' => $hoy->copy()->startOfWeek(CarbonInterface::FRIDAY),
            default => $hoy->copy()->startOfWeek(CarbonInterface::SUNDAY),
        };

        $fechaFinal = $fechaInicial->copy()->addDays(6);

        // 🔹 DÍAS
        $this->dias = [];
        for ($i = 0; $i < 7; $i++) {
            $this->dias[] = $fechaInicial->copy()->addDays($i);
        }

        // 🔹 PRODUCTORES con paginación
        $productores = Productor::with([
            'acopios' => function ($q) use ($fechaInicial, $fechaFinal) {
                $q->whereBetween('fecha', [
                    $fechaInicial->toDateString(),
                    $fechaFinal->toDateString()
                ]);
            },
            'adelantos' => function ($q) use ($fechaInicial, $fechaFinal) {
                $q->select(
                    'productor_id',
                    'fecha',
                    DB::raw('(efectivo + combustible + alimentos + lacteos + otros) as total')
                )
                    ->whereBetween('fecha', [
                        $fechaInicial->toDateString(),
                        $fechaFinal->toDateString()
                    ]);
            }
        ])
            ->where('localidad_id', $this->localidad_id)
            ->where('semana', $this->tipo_semana)
            ->paginate(9);

        $this->numeroFilas = $productores->count();

        $fin = microtime(true);
        $this->tiempo = round($fin - $inicio, 3);

        return view('livewire.acopio.recibos', [
            'productores' => $productores,
            'fechaInicial' => $fechaInicial->format('Y-m-d')
        ]);
    }
}
