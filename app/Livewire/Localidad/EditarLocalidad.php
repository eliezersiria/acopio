<?php

namespace App\Livewire\Localidad;

use Livewire\Component;
use App\Models\Localidad;
use Illuminate\Validation\Rule;
use Carbon\Carbon;

class EditarLocalidad extends Component
{
    public $nombre = '';
    public $localidad_id;
    public $actualizado;
    public $creado;
    public $showModalSendTrash = false;

    public function mount($id)
    {
        //MONTO TODOS LOS DATOS EN EL FORMULARIO DE EDITAR
        $localidad = Localidad::findOrFail($id);
        $this->localidad_id = $localidad->id;
        $this->nombre = $localidad->nombre;
        $this->actualizado = $localidad ? Carbon::parse($localidad->updated_at) : null;
        $this->creado = $localidad ? Carbon::parse($localidad->created_at) : null;
    }
    public function sendTrash($id)
    {
        $localidad = Localidad::findOrFail($id);
        $this->localidad_id = $localidad->id;
        $this->nombre = $localidad->nombre;
        $this->showModalSendTrash = true;
    }

    public function softDelete()
    {
        $localidad = Localidad::find($this->localidad_id);
        if ($localidad) {
            $localidad->delete();
            session()->flash('status', "La categoría $this->nombre fue enviado a la papelera");
            return $this->redirect('/localidad-listar', navigate: true);

        } else {
            session()->flash('error', "No se encontró la categoría");
            $this->showModalSendTrash = false;
        }
        $this->showModalSendTrash = false;

    }

    public function closeModalSendTrash()
    {
        $this->showModalSendTrash = false;
    }
    protected function rules()
    {
        return [
            'nombre' => ['required', 'string', Rule::unique('localidads', 'nombre')->ignore($this->localidad_id)],
        ];
    }
    public function save()
    {
        $this->validate();

        $localidad = Localidad::findOrFail($this->localidad_id);

        $localidad->update([
            'nombre' => $this->nombre,
        ]);
        session()->flash('status', 'Cambios guardados');
    }

    public function render()
    {
        return view('livewire.localidad.editar-localidad');
    }
}
