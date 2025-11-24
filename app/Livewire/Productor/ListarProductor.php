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
    public function updatingBuscar()
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
        //$this->productores = Productor::with('localidad')->orderBy('id', 'desc')->paginate(10);
        $query = Productor::with('localidad')->orderBy('id', 'desc');
        if (!empty($this->search)) {
            $searchTerm = $this->search . '*'; // El término de búsqueda
            // Usaremos la búsqueda Full-Text para 'nombre' y 'cedula'
            // Y usaremos whereHas para buscar en 'localidad'
            $query->where(function ($q) use ($searchTerm) {
                // 1. Búsqueda de Texto Completo (AHORA CON PREFIJOS)
                // El operador '?' reemplaza a $searchTerm, que ahora es, por ejemplo: 'mes*'
                $q->whereRaw("MATCH(nombre, cedula) AGAINST (? IN BOOLEAN MODE)", [$searchTerm])
                    // 2. Búsqueda en la Relación 'localidad' (La mantenemos)
                    ->orWhereHas('localidad', function ($qLocalidad) use ($searchTerm) {
                        // Volvemos a usar el término original SIN el '*' para el filtro LIKE
                        $originalSearchTerm = str_replace('*', '', $searchTerm);
                        $qLocalidad->where('nombre', 'like', '%' . $originalSearchTerm . '%');
                    });
            });
        }

        $this->productores = $query->paginate(10);
        $fin = microtime(true);
        $this->tiempo = round($fin - $inicio, 3);
        $this->numeroFilas = $this->productores->total();

        return view('livewire.productor.listar-productor', [
            'productores' => $this->productores
        ]);
    }
}
