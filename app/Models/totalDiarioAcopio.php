<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class totalDiarioAcopio extends Model
{
    //
    protected $table = 'total_diario_acopios';
    protected $fillable = [
        'fecha',
        'localidad_id',
        'tipo_semana',
        'litros',
    ];
}
