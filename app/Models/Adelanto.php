<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Adelanto extends Model
{
    //
    protected $table = 'adelantos';

    protected $fillable = [
        'productor_id',
        'efectivo',
        'combustible',
        'alimentos',
        'lacteos',
        'otros',
        'fecha',
    ];

    // Un precio semanal pertenece a un productor
    public function productor()
    {
        return $this->belongsTo(Productor::class);
    }
}
