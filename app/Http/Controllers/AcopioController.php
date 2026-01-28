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
    public function resumenSemanal()
    {
        return view('acopio.acopio-resumen-semanal');
    }

    public function listar()
    {
        return view('acopio.listar-acopio');
    }

    public function recibos()
    {
        return view('acopio.receipts');
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
