<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Scout\Searchable;  // ← AGREGAR ESTA LÍNEA


class Productor extends Model
{
    use SoftDeletes; // 👈 permite soft delete
    use Searchable;  // ← AGREGAR ESTO

    // Definir qué campos son buscables
    public function toSearchableArray()
    {
        return [
            'id' => $this->id,
            'nombre' => $this->nombre,
            'localidad' => $this->localidad?->nombre ?? null, // campo para filtrar
        ];
    }

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

    // Configurar índice de Meilisearch
    public function searchableAs()
    {
        return 'productores';
    }
    public function localidad()
    {
        return $this->belongsTo(Localidad::class);
    }

    public function acopios()
    {
        return $this->hasMany(Acopio::class);
    }

}
