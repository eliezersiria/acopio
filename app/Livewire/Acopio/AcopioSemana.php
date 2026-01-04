<?php

namespace App\Livewire\Acopio;

use Livewire\Component;
use App\Models\Acopio;
use App\Models\Adelanto;
use App\Models\Localidad;
use App\Models\Productor;
use App\Models\PrecioLecheSemanal;

use Carbon\Carbon;
use Carbon\CarbonInterface;

class AcopioSemana extends Component
{
    public $tiempo;
    public $numeroFilas;
    public $comarcas;
    public $localidad_id;
    public $localidad;
    public $dias = [];
    public $editando = null;
    public $litros;
    public $editando_precio_litro = null;
    public $precio_leche_semanal;
    public $editando_adelantos = null;
    public $cantidad = 0;
    public $campo;
    public $tipo_semana;
    public $fechaReporte;

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
    // Propiedad computada: texto de la semana
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
            $this->dias[] = $fechaInicial->copy()->addDays($i);
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

    // Propiedad computada para el reporte
    public function getReporteProperty()
    {
        $inicio = microtime(true);
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

        // Generar array de días
        $this->dias = [];
        for ($i = 0; $i < 7; $i++) {
            $this->dias[] = $fechaInicial->copy()->addDays($i);
        }

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


        $reporte = [];

        foreach ($productores as $productor) {
            $clave = $productor->id;
            $precioModel = $productor->preciosSemanales->first();
            $precio = $precioModel?->precio ?? 0;

            if (!isset($reporte[$clave])) {
                $reporte[$clave] = [
                    'productor_id' => $productor->id,
                    'productor'    => $productor->nombre,
                    'litros'       => [],
                    'total_litros' => 0,
                    'precio_semanal' => $precio,
                    'total_cordobas' => 0,
                    'fecha_inicial' => $fechaInicial->format('Y-m-d'),
                    'deduccion_compra' => 0,
                    'total_efectivo' => 0,
                    'total_combustible' => 0,
                    'total_alimentos' => 0,
                    'total_lacteos' => 0,
                    'total_otros' => 0,
                    'total_deducciones' => 0,
                    'neto_recibir' => 0
                ];
            }

            // Sumar litros por fecha
            foreach ($productor->acopios as $acopio) {
                $fecha = $acopio->fecha;
                $litros = (float) $acopio->litros;
                // Guardar litros por fecha
                $reporte[$clave]['litros'][$fecha] = $litros;
                // Sumar litros totales
                $reporte[$clave]['total_litros'] += $litros;
            }
            // Calcular monto total UNA VEZ después de sumar todos los litros
            $reporte[$clave]['total_cordobas'] = $reporte[$clave]['total_litros'] * $precio;
            // Calcular deducción
            $reporte[$clave]['deduccion_compra'] = $reporte[$clave]['total_cordobas'] * (float)0.013;
            //Calcular total_efectivo
            $reporte[$clave]['total_efectivo'] = $productor->total_efectivo;
            //Calcular total_combustible
            $reporte[$clave]['total_combustible'] = $productor->total_combustible;
            //Calcular total_efectivo
            $reporte[$clave]['total_alimentos'] = $productor->total_alimentos;
            //Calcular total lacteos
            $reporte[$clave]['total_lacteos'] = $productor->total_lacteos;
            //Calcular total_otros
            $reporte[$clave]['total_otros'] = $productor->total_otros;
            //Calcular total de deducciones
            $reporte[$clave]['total_deducciones'] = $reporte[$clave]['total_cordobas'] * (float)0.013 +
                $productor->total_efectivo +
                $productor->total_combustible +
                $productor->total_alimentos +
                $productor->total_lacteos +
                $productor->total_otros;
            //Calcular neto a recibir
            $reporte[$clave]['neto_recibir'] = $reporte[$clave]['total_cordobas'] - $reporte[$clave]['total_deducciones'];
        }

        $this->numeroFilas = count($reporte);
        $fin = microtime(true);
        $this->tiempo = round($fin - $inicio, 3);
        return $reporte;
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

    public function editar($productor_id, $fecha)
    {
        $this->editando = [
            'productor_id' => $productor_id,
            'fecha' => $fecha,
        ];

        $acopio = Acopio::where($this->editando)->first();
        $this->litros = intval($acopio?->litros);
    }

    public function guardar()
    {
        if (!$this->editando) return;

        $productorId = $this->editando['productor_id'];
        $fecha       = $this->editando['fecha'];
        $this->litros = intval($this->litros);

        if ($this->litros >= 0) {
            Acopio::updateOrCreate(
                [
                    'productor_id' => $productorId,
                    'fecha' => $fecha,
                ],
                [
                    'litros' => $this->litros,
                    'hora' => now()->format('H:i:s'),
                ]
            );
        }

        $this->editando = null;
        $this->litros = null;
    }

    public function editar_precio_semanal($productor_id, $fecha_inicial)
    {
        $this->editando_precio_litro = [
            'productor_id' => $productor_id,
            'fecha_inicio' => $fecha_inicial,
        ];
        $precio = PrecioLecheSemanal::where($this->editando_precio_litro)->first();
        $this->precio_leche_semanal = intval($precio?->precio);
    }

    public function guardar_precio_semanal_litro()
    {
        if (!$this->editando_precio_litro) return;

        $productorId = $this->editando_precio_litro['productor_id'];
        $fecha       = $this->editando_precio_litro['fecha_inicio'];
        $this->precio_leche_semanal = intval($this->precio_leche_semanal);

        PrecioLecheSemanal::updateOrCreate(
            [
                'productor_id' => $productorId,
                'fecha_inicio' => $fecha,
            ],
            [
                'precio' => $this->precio_leche_semanal,
                'fecha_inicio' => $fecha,
            ]
        );

        $this->editando_precio_litro = null;
        $this->precio_leche_semanal = null;
    }

    public function editar_adelantos($productor_id, $fecha_inicial, $campo)
    {
        $this->editando_adelantos = [
            'productor_id' => $productor_id,
            'fecha' => $fecha_inicial,
            'campo' => $campo,
        ];
        $this->campo = $campo;
        $adelanto = Adelanto::where('productor_id', $productor_id)->where('fecha', $fecha_inicial)->first();
        $this->cantidad = intval($adelanto?->{$campo} ?? 0);
    }

    public function guardar_adelantos()
    {
        if (!$this->editando_adelantos || !$this->campo) {
            return;
        }
        $productorId = $this->editando_adelantos['productor_id'];
        $fecha       = $this->editando_adelantos['fecha'];
        $campo       = $this->campo;
        $valor       = $this->cantidad;

        Adelanto::updateOrCreate(
            [
                'productor_id' => $productorId,
                'fecha' => $fecha,
            ],
            [
                $campo => $valor
            ]
        );
        // Reset estado
        $this->reset(['cantidad', 'campo', 'editando_adelantos']);
    }

    public function render()
    {
        return view('livewire.acopio.acopio-semana', [
            'reporte' => $this->reporte,
            'textoSemana' => $this->textoSemana,
        ]);
    }
}
