<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Grupos extends Model
{
    use HasFactory;

    protected $fillable = [
        'nombre',
        'num_clasificados',
        'subcategoria_id'
    ];

   public function equipos()
{
    return $this->belongsToMany(Equipo::class, 'grupo_equipo', 'grupo_id', 'equipo_id');
}

    public function subcategoria()
    {
        return $this->belongsTo(Subcategory::class, 'subcategoria_id');
    }
}
