<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LocalidadController extends Controller
{
    public function index()
    {
        return view('localidad.localidad');
    }

    public function create()
    {
        return view('localidad.crear-localidad');
    }

    public function listar()
    {
        return view('localidad.list-localidad');
    }

    public function editar($id)
    {
        return view('localidad.edit-comarca', compact('id'));
    }
}
