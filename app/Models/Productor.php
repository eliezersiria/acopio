<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class Productor extends Model
{
    use SoftDeletes; // 👈 permite soft delete

    protected $fillable = [
        'localidad_id',
        'nombre',
        'cedula',
        'telefono',
        'direccion',
        'activo',
        'foto',
        'created_at',
        'updated_at',
        'deleted_at'
    ];
    public function localidad()
    {
        return $this->belongsTo(Localidad::class);
    }

}
