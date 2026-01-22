<?php

namespace App\Livewire\Localidad;

use Livewire\Component;
use App\Models\Localidad;
class ListarLocalidad extends Component
{
    public $localidades;
    public $inicio;
    public $fin;
    public $numeroFilas;
    public $tiempo;
    public function render()
    {
        $inicio = microtime(true);
        $this->localidades = Localidad::all();
        $fin = microtime(true);
        $this->tiempo = round($fin - $inicio, 3);
        $this->numeroFilas = count($this->localidades);
        return view('livewire.localidad.listar-localidad');
    }
}
