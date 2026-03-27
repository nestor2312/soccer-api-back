<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Partido;
class PartidosController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
public function index(Request $request)
{
    $query = Partido::with(['equipoA.grupo.subcategoria.categoria', 'equipoB']);

    // Filtro por Torneo
    if ($request->filled('torneo_id')) {
        $query->whereHas('equipoA.grupo.subcategoria.categoria', function($q) use ($request) {
            $q->where('torneo_id', $request->torneo_id);
        });
    }

    // Filtro por Categoría
    if ($request->filled('categoria_id')) {
        $query->whereHas('equipoA.grupo.subcategoria', function($q) use ($request) {
            $q->where('categoria_id', $request->categoria_id);
        });
    }

    // Filtro por Subcategoría
    if ($request->filled('subcategoria_id')) {
        $query->whereHas('equipoA.grupo', function($q) use ($request) {
            $q->where('subcategoria_id', $request->subcategoria_id);
        });
    }

    // Filtro por Jornada (Corregido para que use la misma estructura de $query)
    if ($request->filled('jornada')) {
        $query->where('jornada', 'LIKE', '%' . $request->jornada . '%');
    }

    // Ejecutamos la consulta y paginamos
    $partidos = $query->orderBy('id', 'desc')->paginate(10);

    return response()->json($partidos);
}

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $partido = new Partido();
        $partido -> equipoA_id = $request->equipoA_id;
        $partido -> equipoB_id = $request->equipoB_id;
        $partido -> marcador1 = $request->marcador1;
        $partido -> marcador2 = $request->marcador2;
        $partido -> equipoA()->associate( $request->equipoA_id );
        $partido -> equipoB()->associate( $request->equipoB_id );
        $partido -> fecha = $request->fecha;
        $partido -> hora = $request->hora;
           $partido -> jornada = $request->jornada;
      
        $partido ->save();
        return response()->json($partido, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
      $partido = Partido::with([
        'equipoA.grupo.subcategoria.categoria.torneo',
        'equipoB.grupo.subcategoria.categoria.torneo'
    ])->findOrFail($id);

    return response()->json($partido);
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
        $partido = Partido::find($id);
    
        if (!$partido) {
            return response()->json(['message' => 'Partido no encontrado'], 404);
        }
    
        $validatedData = $request->validate([
            'equipoA_id' => 'required|integer|exists:equipos,id',
            'equipoB_id' => 'required|integer|exists:equipos,id',
            'marcador1' => 'nullable|integer|min:0',
            'marcador2' => 'nullable|integer|min:0',
            'fecha' => 'nullable',
            'hora' => 'nullable',
            'jornada' => 'nullable',
        ]);
    
        $partido->update($validatedData);
    
        return response()->json(['message' => 'Partido actualizado correctamente', 'partido' => $partido], 200);
    }
    

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $partido = Partido::findOrFail($id);  
        $partido->delete();
        return response()->json(['message' => 'partido eliminado correctamente']);
    }
}
