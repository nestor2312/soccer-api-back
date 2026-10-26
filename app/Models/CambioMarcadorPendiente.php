<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CambioMarcadorPendiente extends Model
{
      use HasFactory;

    protected $table = 'cambios_marcador_pendientes';

    protected $fillable = [
        'partido_id',
        'subcategoria_id',
        'notificado',
    ];

    protected $casts = [
        'notificado' => 'boolean',
    ];

    public function partido()
    {
        return $this->belongsTo(Partido::class, 'partido_id');
    }
}
