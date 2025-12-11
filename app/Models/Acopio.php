<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Acopio extends Model
{
    protected $fillable = [
        'productor_id',
        'localidad_id',
        'fecha',
        'hora',
        'litros',
        'precio_litro',
        'total_pagado',
        'observaciones',
    ];

    public function productor()
    {
        return $this->belongsTo(Productor::class);
    }

    public function comunidad()
    {
        return $this->belongsTo(Localidad::class);
    }
}

