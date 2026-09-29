<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Equipo extends Model
{
    use HasFactory;

    protected $fillable = [
        'nombre',
        'archivo',
        'color_hover'
    ];


       public function grupo()
{
    // Parámetros: ModeloRelacionado, 'tabla_pivote', 'fk_este_modelo', 'fk_modelo_destino'
    return $this->belongsToMany(Grupos::class, 'grupo_equipo', 'equipo_id', 'grupo_id');
}

   public function grupos()
{
    // Parámetros: ModeloRelacionado, 'tabla_pivote', 'fk_este_modelo', 'fk_modelo_destino'
    return $this->belongsToMany(Grupos::class, 'grupo_equipo', 'equipo_id', 'grupo_id');
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