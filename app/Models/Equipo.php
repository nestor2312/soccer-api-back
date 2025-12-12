<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Equipo extends Model
{
    use HasFactory;
      protected $fillable = ['grupo_id','nombre', 'archivo','color_hover'];

      
      public function grupo()
      {
          return $this->belongsTo(Grupos::class); 
      }

      public function jugadores(){
        return $this->hasMany(Player::class);
    }


      public function partidos(){
        return $this->hasMany(Partido::class);
    }
    public function eliminatorias(){
      return $this->hasMany(Eliminatoria::class);
  }
  
}
