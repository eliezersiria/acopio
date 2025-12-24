<?php

namespace App\Livewire\Acopio;

use Livewire\Component;
use App\Models\Productor;
use App\Models\Adelanto;

class AgregarAcopio extends Component
{
    public $productorquery = '';
    public $highlightIndex = -1; // No hay selección por defecto
    public $productores = [];
    public $productor_id = null;
    public $localidad;
    public $fecha;
    public $efectivo;
    public $combustible;
    public $alimentos;
    public $lacteos;
    public $otros;
    protected $rules = [
        'productor_id' => 'required|exists:productors,id',
        'fecha' => 'required'
    ];
    public function mount()
    {
        //$this->fecha = now()->format('Y-m-d');
    }

    public function updatedProductorquery()
    {
        //Reinicia la selección cada vez que el usuario escriba:
        $this->highlightIndex = -1;

        $this->reset('localidad'); // limpia el dropdown
        //$this->reset('localidad_id');

        if (!empty($this->productorquery)) {

            $this->productores = Productor::search($this->productorquery)
                ->query(function ($q) {
                    // Cargar relación localidad
                    $q->with('localidad');
                })->take(8)->get();

        } else {
            $this->productores = [];
        }
    }

    public function selectProductor($id)
    {
        $this->productores = [];
        $prod = collect($this->productores)->firstWhere('id', $id);

        if (!$prod) {
            $prod = Productor::find($id);
        }

        if ($prod) {
            $this->productor_id = $prod->id;
            $this->productorquery = $prod->nombre;

            $this->localidad = $prod->localidad?->nombre . " ✔" ?? 'Sin localidad';
        }
    }

    public function incrementHighlight()
    {
        if ($this->highlightIndex === count($this->productores) - 1) {
            $this->highlightIndex = 0; // vuelve al inicio
            return;
        }

        $this->highlightIndex++;
    }

    public function decrementHighlight()
    {
        if ($this->highlightIndex === 0) {
            $this->highlightIndex = count($this->productores) - 1; // vuelve al final
            return;
        }

        $this->highlightIndex--;
    }

    public function selectHighlighted()
    {
        if ($this->highlightIndex >= 0 && isset($this->productores[$this->highlightIndex])) {
            $prod = $this->productores[$this->highlightIndex];
            $this->selectProductor($prod->id);
        }
    }

    public function SaveAdelanto()
    {
        $this->validate();

        Adelanto::create([
            'productor_id' => $this->productor_id,
            'efectivo' => $this->efectivo,
            'combustible' => $this->combustible,
            'alimentos' => $this->alimentos,
            'lacteos' => $this->lacteos,
            'otros' => $this->otros,
            'fecha' => $this->fecha
        ]);

        // Resetear solo los campos que quieres limpiar, sin tocar fecha y hora
        $this->reset();
        //$this->fecha = now()->format('Y-m-d');
        session()->flash('status', 'Acopio registrado correctamente!');
    }
    public function render()
    {
        return view('livewire.acopio.agregar-acopio');
    }
}
