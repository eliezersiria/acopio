<?php

namespace App\Livewire\Productor;

use App\Models\Localidad;
use App\Models\Productor;

use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Str;

use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

use Illuminate\Support\Facades\Storage;


class AgregarProductor extends Component
{
    use WithFileUploads;  // 👈 OBLIGATORIO para subir archivos
    public $nombre;
    public $cedula;
    public $telefono;
    public $localidad_id;
    public $direccion;
    public $semana;
    public $foto;
    public $localidades;

    public function mount()
    {
        $this->localidades = Localidad::orderBy('nombre', 'asc')->get();
    }

    protected $rules = [
        'nombre' => 'required|string|min:5',
        'cedula' => 'unique:productors,cedula',
        'telefono' => 'required|numeric|min:8',
        'localidad_id' => 'required',
        'direccion' => 'required|string|min:5',
        'semana' => 'required',
        'foto' => 'image|max:2048',
    ];

    public function saveProductor()
    {
        $this->validate();

        try {

            $path = null;

            if ($this->foto) {
                // Generar nombre único
                $filename = time() . Str::random() . ".webp";

                // Crear nueva instancia de ImageManager
                $manager = new ImageManager(new Driver());
                $image = $manager->read($this->foto->getRealPath());

                // Redimensionar sin perder ratio
                $image = $image->scaleDown(height: 192)->toWebp(70);

                // Asegurarse de que la carpeta exista
                $directory = public_path('images/productores');
                if (!file_exists($directory)) {
                    mkdir($directory, 0755, true);
                }

                // Guardar imagen
                $image->save(public_path("images/productores/$filename"));

                // Guardar ruta para la base de datos
                $path = "images/productores/$filename";                
            }


            Productor::create([
                'nombre' => $this->nombre,
                'cedula' => $this->cedula,
                'telefono' => $this->telefono,
                'localidad_id' => $this->localidad_id,
                'semana' => $this->semana,
                'direccion' => $this->direccion,
                'foto' => $path,
            ]);
            // Limpiar el campo
            $this->reset(['nombre', 'cedula', 'telefono', 'localidad_id', 'direccion', 'semana', 'foto']);
            // Mensaje de éxito
            session()->flash('status', 'Productor agregado correctamente');            
        } catch (\Exception $e) {


            session()->flash('error', 'Ocurrió un error: ' . $e->getMessage());
        }
    }
    public function render()
    {
        return view('livewire.productor.agregar-productor');
    }
}
