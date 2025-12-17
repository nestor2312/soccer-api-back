<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Partido extends Model
{
    use HasFactory;

    protected $fillable = ['marcador1','marcador2','equipoA_id', 'equipoB_id','hora','fecha'];
    public function equipoA()
    {
        return $this->belongsTo(Equipo::class, 'equipoA_id');
    }
    
    public function equipoB()
    {
        return $this->belongsTo(Equipo::class, 'equipoB_id');
    }
}
