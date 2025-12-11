<?php

namespace App\Livewire\Acopio;

use Livewire\Component;
use App\Models\Productor;
use App\Models\Acopio;

class AgregarAcopio extends Component
{
    public $productorquery = '';
    public $highlightIndex = -1; // No hay selección por defecto
    public $productores = [];

    public $productor_id = null;
    public $localidad;
    public $localidad_id;
    public $fecha;
    public $hora;
    public $litros;
    public $precio_litro;
    public $total_pagado;
    protected $rules = [
        'productor_id' => 'required|exists:productors,id',
        'localidad_id' => 'required|exists:localidads,id',
        'litros' => 'required|numeric',
        'precio_litro' => 'required|numeric',
        'total_pagado' => 'required|numeric|min:0.01',
    ];
    public function mount()
    {
        $this->fecha = now()->format('Y-m-d');
        $this->hora = now()->format('H:i:s');
    }

    public function updatedProductorquery()
    {
        //Reinicia la selección cada vez que el usuario escriba:
        $this->highlightIndex = -1;

        $this->reset('localidad'); // limpia el dropdown
        $this->reset('localidad_id');

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
            $this->localidad_id = $prod->localidad?->id ?? 'Sin id';
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

    public function updatedLitros()
    {
        $this->total_pagado = ((float) ($this->litros ?? 0)) * ((float) ($this->precio_litro ?? 0));
    }

    public function updatedPrecioLitro()
    {
        $this->total_pagado = ((float) ($this->litros ?? 0)) * ((float) ($this->precio_litro ?? 0));
    }

    public function SaveAcopio()
    {
        $this->validate();

        // Multiplicación en PHP al momento de guardar
        $total = ((float) ($this->litros ?? 0)) * ((float) ($this->precio_litro ?? 0));

        Acopio::create([
            'productor_id' => $this->productor_id,
            'localidad_id' => $this->localidad_id,
            'fecha' => $this->fecha,
            'hora' => $this->hora,
            'litros' => $this->litros,
            'precio_litro' => $this->precio_litro,
            'total_pagado' => $total,
        ]);

        // Resetear solo los campos que quieres limpiar, sin tocar fecha y hora
        $this->reset();
        $this->fecha = now()->format('Y-m-d');
        $this->hora = now()->format('H:i:s');
        session()->flash('status', 'Acopio registrado correctamente!');
    }
    public function render()
    {
        return view('livewire.acopio.agregar-acopio');
    }
}
