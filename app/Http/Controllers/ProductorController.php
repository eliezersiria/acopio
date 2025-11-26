<?php

namespace App\Http\Controllers;

use App\Models\Productor;
use Illuminate\Http\Request;

class ProductorController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //return view('productor.productor');
        return view('productor.productor-listar');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('productor.productor-agregar');
    }

    public function listar()
    {
        return view('productor.productor-listar');
    }

    public function editar($id)
    {
        return view('productor.productor-editar', compact('id'));
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
    public function show(Productor $productor)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Productor $productor)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Productor $productor)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Productor $productor)
    {
        //
    }
}
