<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Player;
use App\Models\Equipo;
class PlayersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $jugadores = Player::with('equipo')->get();
        return $jugadores;
    }
    public function getJugadoresPorEquipo($equipoId)
    {
        $equipo = Equipo::with('jugadores')->find($equipoId);
    
        if (!$equipo) {
            return response()->json(['error' => 'Equipo no encontrado'], 404);
        }
    
        return response()->json($equipo);
    }
    
    
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $jugador = new Player();
        $jugador -> equipo_id = $request->get('equipo_id');
        $jugador -> nombre = $request->get('nombre');
        $jugador -> apellido = $request->get('apellido');
        $jugador -> edad = $request->get('edad');
        $jugador -> numero = $request->get('numero');
        $jugador -> card_amarilla = $request->get('card_amarilla');
        $jugador -> card_roja = $request->get('card_roja');
        $jugador->goles = $request->goles;
        $jugador->asistencias = $request->asistencias;
        $jugador ->save();
        return response()->json($jugador, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $jugador = Player::with('equipo')->find($id);
        if (!$jugador) {
            return response()->json(['message' => 'Jugador no encontrado'], 404);
        }

        return response()->json($jugador);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $jugador = Player::find($id);
    
        if (!$jugador) {
            return response()->json(['message' => 'jugador no encontrado'], 404);
        }
    
        $jugador->equipo_id = $request->equipo_id;
        $jugador->nombre = $request->nombre;
        $jugador->apellido = $request->apellido;
        $jugador->edad = $request->edad;
        $jugador->numero = $request->numero;
        $jugador->card_amarilla = $request->card_amarilla;
        $jugador->card_roja = $request->card_roja;
        $jugador->goles = $request->goles;
        $jugador->asistencias = $request->asistencias;
        $jugador->save();
    
        return response()->json(['message' => 'jugador actualizado correctamente', 'partido' => $jugador], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $jugador = Player::findOrFail($id);  
        $jugador->delete();
        return response()->json(['message' => 'jugador eliminado correctamente']);
    }
}
