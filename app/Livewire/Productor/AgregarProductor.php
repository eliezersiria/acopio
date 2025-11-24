<?php

namespace App\Livewire\Productor;

use App\Models\Localidad;
use App\Models\Productor;

use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Str;

use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class AgregarProductor extends Component
{
    use WithFileUploads;  // 👈 OBLIGATORIO para subir archivos
    public $nombre;
    public $cedula;
    public $telefono;
    public $localidad_id;
    public $direccion;
    public $foto;
    public $localidades;

    public function mount()
    {
        $this->localidades = Localidad::orderBy('nombre', 'asc')->get();
    }

    protected $rules = [
        'nombre' => 'required|string|min:5',
        'cedula' => 'required|string|min:14|unique:productors,cedula',
        'telefono' => 'required|numeric|min:8',
        'localidad_id' => 'required',
        'direccion' => 'required|string|min:5',
        'foto' => 'required|image|max:2048',
    ];

    public function saveProductor()
    {
        $this->validate();

        try {

            $path = null;

            if ($this->foto) {
                // Guardar imagen si se subió 
                $filename = time() . Str::random() . ".webp";
                // create new manager instance with desired driver
                $manager = new ImageManager(new Driver());
                // read image
                $image = $manager->read($this->foto->getRealPath());
                //Resize pero sin perder el ratio
                $image = $image->scaleDown(height: 192)->toWebp(70);
                //Guardamos
                $image->save(public_path("storage/images/productores/$filename"));

                $path = "images/productores/$filename";
            }

            Productor::create([
                'nombre' => $this->nombre,
                'cedula' => $this->cedula,
                'telefono' => $this->telefono,
                'localidad_id' => $this->localidad_id,
                'direccion' => $this->direccion,
                'foto' => $path,
            ]);
            // Limpiar el campo
            $this->reset(['nombre', 'cedula', 'telefono', 'localidad_id', 'direccion', 'foto']);
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
