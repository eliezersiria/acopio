<?php

namespace App\Livewire\Productor;

use App\Models\Localidad;
use App\Models\Productor;

use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;

class EditarProductor extends Component
{
    use WithFileUploads;  // 👈 OBLIGATORIO para subir archivos
    public $productor_id;
    public $nombre;
    public $cedula;
    public $telefono;
    public $localidad_id;
    public $direccion;
    public $semana;
    public $foto;
    public $localidades;

    public function mount($id)
    {
        $this->localidades = Localidad::orderBy('nombre', 'asc')->get();
        //MONTO TODOS LOS DATOS EN EL FORMULARIO DE EDITAR
        $productor = Productor::with('localidad')->findOrFail($id);
        $this->productor_id = $productor->id;
        $this->localidad_id = $productor->localidad_id;
        $this->nombre = $productor->nombre;
        $this->cedula = $productor->cedula;
        $this->telefono = $productor->telefono;
        $this->direccion = $productor->direccion;
        $this->semana = $productor->semana;
        $this->foto = $productor->foto;
    }
    public function updateProductor()
    {
        $rules = [
            'nombre' => 'required|string|min:5',
            'cedula' => [Rule::unique('productors', 'cedula')->ignore($this->productor_id)],
            'telefono' => 'required|numeric|',
            'localidad_id' => 'required|numeric',
            'direccion' => 'required|string|',
            'semana' => 'required',
        ];

        $this->validate($rules);

        // Solo validar la imagen si el usuario subió una nueva
        if ($this->foto instanceof TemporaryUploadedFile) {
            $rules['foto'] = 'image|max:2048';
        }

        $productor = Productor::findOrFail($this->productor_id);
        $path = $productor->foto; // mantener la imagen actual por defecto

        // Si hay una nueva imagen, eliminar la anterior y guardar la nueva
        if ($this->foto instanceof TemporaryUploadedFile) {

            // Guardar imagen si se subió 
            $filename = time() . Str::random() . ".webp";
            // create new manager instance with desired driver
            $manager = new ImageManager(new Driver());
            // read image
            $image = $manager->read($this->foto->getRealPath());
            //Resize pero sin perder el ratio
            $image = $image->scaleDown(height: 192)->toWebp(70);
            //Guardamos
            $image->save(public_path("images/productores/$filename"));

            $path = "images/productores/$filename";

            if ($productor->foto && Storage::disk('public')->exists($productor->foto)) {
                //Eliminamos la imagen actual
                Storage::disk('public')->delete($productor->foto);
            }

        } else {

        }

        $productor->update([
            'nombre' => $this->nombre,
            'cedula' => $this->cedula,
            'telefono' => $this->telefono,
            'localidad_id' => $this->localidad_id,
            'direccion' => $this->direccion,
            'semana' => $this->semana,
            'foto' => $path,
        ]);

        session()->flash('status', "El registro $this->nombre fue actualizado");

    }
    public function render()
    {
        return view('livewire.productor.editar-productor');
    }
}
