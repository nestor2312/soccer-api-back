<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Grupos extends Model
{
    use HasFactory;

    protected $fillable = ['nombre','num_clasificados','subcategoria_id'];

    public function equipos()
    {
        return $this->hasMany(Equipo::class, 'grupo_id');
    }

    public function categoria()
    {
        return $this->hasMany(Grupos::class);
    }

    public function subcategoria()
      {
          return $this->belongsTo(SubCategory::class, 'subcategoria_id'); 
      }


}
