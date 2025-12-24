<?php

namespace App\Livewire\Productor;

use Livewire\Component;
use Livewire\WithPagination;

use App\Models\Productor;

class ListarProductor extends Component
{
    use WithPagination;
    public $inicio;
    public $fin;
    public $numeroFilas;
    public $tiempo;
    public $search = '';
    // Cada vez que escriba algo, vuelve a la página 1
    public function updatingSearch()
    {
        $this->resetPage();
    }
    public function clearSearch()
    {
        // 1. Resetear la propiedad de búsqueda. Esto actualizará el input a vacío.
        $this->search = '';

        // 2. Ejecutar la función que resetea la paginación para mostrar la primera página
        // con todos los resultados, como si el usuario hubiera borrado el texto.
        $this->resetPage();
    }
    public function render()
    {
        $inicio = microtime(true);
        // Búsqueda con Scout (Meilisearch)
        $productores = Productor::search($this->search)
            ->query(function ($q) {
                $q->with('localidad');
            })->paginate(10);

        //$this->productores = $query->paginate(10);
        $fin = microtime(true);
        $this->tiempo = round($fin - $inicio, 3);
        $this->numeroFilas = $productores->total();

        return view('livewire.productor.listar-productor', compact('productores'));
    }
}
