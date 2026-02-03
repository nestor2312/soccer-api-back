<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EventoPartido extends Model
{
    use HasFactory;
      protected $table = 'eventos_partido';

    protected $fillable = [
        'partido_id',
        'equipo_id',
        'jugador_id',
        'tipo_evento',
        'minuto'
    ];

    public function partido() {
        return $this->belongsTo(Partido::class);
    }

    public function equipo() {
        return $this->belongsTo(Equipo::class);
    }

    public function jugador() {
        return $this->belongsTo(Player::class);
    }
}
