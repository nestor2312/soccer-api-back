<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Player;
use App\Models\EventoPartido;


use App\Models\Partido;

class EventoPartidoController extends Controller
{
    public function index($partidoId)
{
    return EventoPartido::with('jugador','equipo')
        ->where('partido_id', $partidoId)
        ->orderBy('created_at')
        ->get();
}


public function getJugadores($id)
{
    // 1. Buscamos el partido con sus relaciones. 
    // Usamos 'equipoA.players' asumiendo que en tu modelo Equipo la relación se llama 'players'
    $partido = Partido::with(['equipoA.jugadores', 'equipoB.jugadores'])->findOrFail($id);

    // 2. Devolvemos el formato exacto que tu React está buscando
    return response()->json([
        'equipoA' => $partido->equipoA,
        'equipoB' => $partido->equipoB
    ]);
}


    public function store(Request $request, $partidoId)
{
    $request->validate([
        'equipo_id' => 'required|exists:equipos,id',
        'jugador_id' => 'required|exists:players,id',
        'tipo_evento' => 'required|in:gol,asistencia,amarilla,roja',
        'minuto' => 'nullable|string'
    ]);

    // Validar que el equipo pertenezca al partido
    $partido = Partido::with(['equipoA', 'equipoB'])->findOrFail($partidoId);

    if (!in_array($request->equipo_id, [$partido->equipoA_id, $partido->equipoB_id])) {
        return response()->json(['error' => 'Equipo no pertenece al partido'], 403);
    }

    // Validar jugador pertenece al equipo
    $jugador = Player::where('id', $request->jugador_id)
        ->where('equipo_id', $request->equipo_id)
        ->firstOrFail();

    $evento = EventoPartido::create([
        'partido_id' => $partidoId,
        'equipo_id' => $request->equipo_id,
        'jugador_id' => $request->jugador_id,
        'tipo_evento' => $request->tipo_evento,
        'minuto' => $request->minuto
    ]);

    return response()->json($evento, 201);
}

  public function destroy($id)
    {
        $evento = EventoPartido::findOrFail($id);  
        $evento->delete();
        return response()->json(['message' => 'evento eliminado correctamente']);
    }

}
