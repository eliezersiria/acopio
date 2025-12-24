<?php

namespace App\Http\Controllers;

use App\Models\Acopio;
use App\Models\Localidad;
use App\Models\Productor;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Carbon\CarbonInterface;

class AcopioController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('acopio.acopio');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('acopio.crear-acopio');
    }

    public function listar()
    {
        return view('acopio.listar-acopio');
    }


    public function listar_C()
    {
        Carbon::setLocale('es');
        $inicio = microtime(true);
        $hoy = now();

        $fechaInicial = $hoy->copy()->startOfWeek(CarbonInterface::FRIDAY);
        $fechaFinal   = $fechaInicial->copy()->addDays(6);
        //$fechaInicial = Carbon::parse('2025-12-12');
        //$fechaFinal   = Carbon::parse('2025-12-18');

        // Verificamos si el mes y año son iguales
        if ($fechaInicial->format('m-Y') === $fechaFinal->format('m-Y')) {
            // Mismo mes y año
            $textoSemana = 'Semana del '
                . $fechaInicial->format('d')
                . ' al '
                . $fechaFinal->format('d')
                . ' de '
                . $fechaInicial->locale('es')->isoFormat('MMMM');
        } else {
            // Meses o años diferentes
            $textoSemana = 'Semana del '
                . $fechaInicial->format('d') . ' '
                . $fechaInicial->locale('es')->isoFormat('MMMM') . ' al '
                . $fechaFinal->format('d') . ' '
                . $fechaFinal->locale('es')->isoFormat('MMMM') . ' '
                . $fechaFinal->format('Y');
        }

        //AQUÍ se crea $dias
        $dias = [];

        for ($i = 0; $i < 7; $i++) {
            $dias[] = $fechaInicial->copy()->addDays($i);
        }

        $localidad_id = Localidad::first()->id;
        $localidad = Localidad::find($localidad_id)->nombre; // busca por PK

        /*
        $acopios = Acopio::with(['productor.localidad'])
            ->whereBetween('fecha', [
                $fechaInicial->format('Y-m-d'),
                $fechaFinal->format('Y-m-d')
            ])
            ->whereHas('productor', function ($query) use ($localidad_id) {
                $tipo_semana = 'A';
                // 1️⃣ tipo_semana = A (tabla productores)
                $query->where('semana', $tipo_semana)

                    // 2️⃣ localidad = Caño Blanco (tabla localidads)
                    ->whereHas('localidad', function ($q) use ($localidad_id) {
                        $q->where('id', $localidad_id);
                    });
            })
            ->get();
            */


        $productores = Productor::with([
            'localidad',
            'acopios' => function ($query) use ($fechaInicial, $fechaFinal) {
                $query->whereBetween('fecha', [
                    $fechaInicial->format('Y-m-d'),
                    $fechaFinal->format('Y-m-d')
                ]);
            }
        ])
            ->where('semana', 'A')
            ->whereHas('localidad', function ($q) use ($localidad_id) {
                $q->where('id', $localidad_id);
            })
            ->get();


        $reporte = [];
        /*
        foreach ($acopios as $acopio) {

            $productor_id = $acopio->productor->id;
            $productor = $acopio->productor->nombre;
            $fecha     = $acopio->fecha;

            // Clave única por productor
            $clave = $productor_id;

            if (!isset($reporte[$clave])) {
                $reporte[$clave] = [
                    'productor' => $productor,
                    'litros'      => [],
                    'total_litros'  => 0
                ];
            }

            $reporte[$clave]['litros'][$fecha] = (float) $acopio->litros;
            // Sumamos al total semanal
            $reporte[$clave]['total_litros'] += (float) $acopio->litros;
        }
            */

        foreach ($productores as $productor) {

            $productor_id = $productor->id;
            $nombre = $productor->nombre;
            //$fecha     = $productor->acopios->fecha;

            // Clave única por productor
            $clave = $productor_id;

            if (!isset($reporte[$clave])) {
                $reporte[$clave] = [
                    'productor_id' => $productor_id,
                    'productor' => $nombre,
                    'litros'      => [],
                    'total_litros'  => 0
                ];
            }

            // Recorremos todos los acopios del productor
            foreach ($productor->acopios as $acopio) {
                $fecha = $acopio->fecha;
                $reporte[$clave]['litros'][$fecha] = (float) $acopio->litros;
                $reporte[$clave]['total_litros'] += (float) $acopio->litros;
            }
        }

        //Extraer todas las comarcas para ponerlas al estilo excel
        $comarcas = Localidad::all();
        $numeroFilas = count($reporte);
        $fin = microtime(true);
        $tiempo = round($fin - $inicio, 3);

        return view('acopio.listar-acopio', compact('dias', 'reporte', 'tiempo', 'numeroFilas', 'localidad', 'textoSemana', 'comarcas', 'localidad_id'));
    }
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Acopio $acopio)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Acopio $acopio)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Acopio $acopio)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Acopio $acopio)
    {
        //
    }
}
