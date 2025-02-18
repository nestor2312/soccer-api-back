<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Torneo;
class TorneoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $torneos = Torneo::orderBy('id', 'desc')->get();
        return $torneos;
    }

      public function paginador()
    {
        $torneos = Torneo::orderBy('id', 'desc')->paginate(10);
        return $torneos;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $torneo = new Torneo();
        $torneo->nombre = $request->nombre;
        $torneo->save();
        return response()->json($torneo, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
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
        // Validar entrada
        $request->validate([
            'nombre' => 'required|string|max:25',
        ]);
    
        // Buscar el torneo
        $torneo = Torneo::find($id);
    
        // Verificar si el torneo existe
        if (!$torneo) {
            return response()->json(['message' => 'Torneo no encontrado'], 404);
        }
    
        // Actualizar y guardar
        $torneo->nombre = $request->nombre;
        $torneo->save();
    
        // Retornar la respuesta
        return response()->json($torneo, 200); // Cambiado a 200 para indicar éxito
    }
    

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        // Encuentra el torneo por ID
        $torneo = Torneo::findOrFail($id);

        // Elimina el grupo
        $torneo->delete();

        return response()->json(['message' => 'torneo eliminado correctamente']);
    }
}
