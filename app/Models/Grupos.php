<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Grupos extends Model
{
    use HasFactory;

    protected $fillable = ['nombre','num_clasificados'];

    public function equipos()
    {
        return $this->hasMany(Equipo::class);
    }

    public function categoria()
    {
        return $this->hasMany(Grupos::class);
    }

    public function subcategoria()
      {
          return $this->belongsTo(SubCategory::class); 
      }


}
