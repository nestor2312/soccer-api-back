<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    public function torneo()
    {
               return $this->belongsTo(Torneo::class, 'torneo_id'); 
    }

     // Relación con Subcategorías
    public function subcategorias()
    {
        return $this->hasMany(SubCategory::class, 'categoria_id');
    }
}
