<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Localidad extends Model
{
    use SoftDeletes; // 👈 permite soft delete
    protected $fillable = ['id', 'nombre'];

    public function productores()
    {
        return $this->hasMany(Productor::class);
    }

    public function acopios()
    {
        return $this->hasManyThrough(
            Acopio::class,
            Productor::class,
            'localidad_id', // FK en productores
            'productor_id', // FK en acopios
            'id',
            'id'
        );
    }
}
