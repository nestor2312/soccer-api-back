<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Player;
use App\Models\EventoPartido;
use App\Models\Partido;
use App\Models\Eliminatoria;

class EventoPartidoController extends Controller
{
    // 🔹 Obtener eventos (partido o eliminatoria)
  public function index(Request $request, $id = null)
{
    $query = EventoPartido::with(['jugador', 'equipo']);

    // Tomamos el ID de la URL o de los parámetros
    $pId = $request->partido_id;
    $eId = $request->eliminatoria_id;

    // Si pasaste un ID por la URL (/partidos/{id}/eventos)
    if ($id) {
        // Buscamos eventos que coincidan con ese ID ya sea en partido O en eliminatoria
        $query->where(function($q) use ($id) {
            $q->where('partido_id', $id)
              ->orWhere('eliminatoria_id', $id);
        });
    } else {
        // Si no hay ID en URL, usamos los filtros de los params
        if ($pId) $query->where('partido_id', $pId);
        if ($eId) $query->where('eliminatoria_id', $eId);
    }

    // Opcional: Filtrar por instancia si el usuario la manda (ida, vuelta, normal)
    if ($request->has('instancia')) {
        $query->where('instancia', $request->instancia);
    }

    return $query->orderBy('minuto', 'asc')->get();
}

    // 🔹 Obtener jugadores (partido o eliminatoria)
    public function getJugadores(Request $request, $id)
    {
        if ($request->get('tipo') === 'eliminatoria') {
         
            $data = Eliminatoria::with(['equipoAa.jugadores', 'equipoB.jugadores'])->findOrFail($id);

            return response()->json([
                'equipoA' => $data->equipoAa,
                'equipoB' => $data->equipoB
            ]);
        }

        $partido = Partido::with(['equipoA.jugadores', 'equipoB.jugadores'])->findOrFail($id);

        return response()->json([
            'equipoA' => $partido->equipoA,
            'equipoB' => $partido->equipoB
        ]);
    }

    // 🔹 Guardar evento
public function store(Request $request)
{
    $request->validate([
        'equipo_id'       => 'required|exists:equipos,id',
        'jugador_id'      => 'required|exists:players,id',
        'tipo_evento'     => 'required|in:gol,asistencia,amarilla,roja,gol_penal,fallo_penal',
        'minuto'          => 'nullable|string',
        'instancia'       => 'nullable|in:normal,ida,vuelta,tanda_penales',
        // REGLA CORREGIDA: required_without
        'partido_id'      => 'required_without:eliminatoria_id|nullable|exists:partidos,id',
        'eliminatoria_id' => 'required_without:partido_id|nullable|exists:eliminatorias,id',
    ]);

    // ... (tus validaciones de pertenencia de equipo/jugador están bien)

    // Crear evento
    $evento = EventoPartido::create([
        'partido_id'      => $request->partido_id,
        'eliminatoria_id' => $request->eliminatoria_id,
        'equipo_id'       => $request->equipo_id,
        'jugador_id'      => $request->jugador_id,
        'tipo_evento'     => $request->tipo_evento,
        'instancia'       => $request->instancia ?? 'normal',
        'minuto'          => $request->minuto
    ]);

    // 🔥 CRÍTICO: Cargar relaciones para que React vea equipo->nombre y jugador->nombre
    return response()->json($evento->load(['jugador', 'equipo']), 201);
}

    // 🔹 Eliminar evento
    public function destroy($id)
    {
        $evento = EventoPartido::findOrFail($id);
        $evento->delete();

        return response()->json(['message' => 'Evento eliminado correctamente']);
    }
}