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

}
