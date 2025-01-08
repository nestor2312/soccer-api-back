<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Eliminatoria extends Model
{
    use HasFactory;

      protected $fillable = [ 'equipo_a_id',
      'equipo_b_id',
      'marcador1_ida',
      'marcador2_ida',
      'marcador1_vuelta',
      'marcador2_vuelta',
      'marcador1_penales',
      'marcador2_penales',
      'numPartido',
      'subcategoria_id',
      'tipo_eliminatoria',
      'tipo_partido',];

    public function equipoAa()
    {
        return $this->belongsTo(Equipo::class, 'equipo_a_id');
    }

    public function equipoB()
    {
        return $this->belongsTo(Equipo::class, 'equipo_b_id');
    }

    public function subcategoria()
    {
        return $this->belongsTo(Subcategory::class);
    }
}
