<?php

namespace App\Livewire\Localidad;

use Livewire\Component;
use App\Models\Localidad;

class CrearLocalidad extends Component
{
    public $nombre = '';

    protected function rules()
    {
        return [
            'nombre' => 'required|min:5|unique:localidads,nombre'            
        ];
    }
    public function save()
    {
        $this->validate();

        Localidad::create([
            'nombre' => $this->nombre,            
        ]);
        // Limpiar el campo
        $this->nombre = '';
        // Mensaje de éxito
        session()->flash('status', 'Comarca agregada correctamente');
    }

    public function render()
    {
        return view('livewire.localidad.crear-localidad');
    }
}
