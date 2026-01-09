<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PrecioLecheSemanal extends Model
{
    //
    protected $table = 'precio_leche_semanals';

    protected $fillable = [
        'productor_id',
        'localidad_id',
        'precio',
        'fecha_inicio',
    ];

    // Un precio semanal pertenece a un productor
    public function productor()
    {
        return $this->belongsTo(Productor::class);
    }

    public function localidad()
    {
        return $this->belongsTo(Localidad::class);
    }
}
