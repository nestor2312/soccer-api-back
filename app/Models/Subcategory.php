<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subcategory extends Model
{
    use HasFactory;
    //  public function grupos()
    // {
    //     return $this->hasMany(Grupos::class);
    // }

    public function equipos()
    {
        return $this->hasManyThrough(Equipo::class, Grupos::class, 'subcategoria_id', 'grupo_id');
    }

    public function categoria()
    {
        return $this->belongsTo(Category::class);
    }

    public function eliminatorias()
    {
        return $this->hasMany(Eliminatoria::class);
    }
}
