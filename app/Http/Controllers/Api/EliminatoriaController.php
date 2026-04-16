<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Eliminatoria;
class EliminatoriaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
public function index() {
    $eliminatorias = Eliminatoria::with('equipoAa', 'equipoB')->get();
    
    // Agrupamos para que el frontend sepa separar las llaves
    return response()->json(
        $eliminatorias->groupBy(fn($item) => $item->nombre_fase ?? 'Principal')->map(fn($fase) => [
            'octavos' => $fase->where('numPartido', 1)->values(),
            'cuartos' => $fase->where('numPartido', 2)->values(),
            'semis'   => $fase->where('numPartido', 3)->values(),
            'final'   => $fase->where('numPartido', 4)->values(),
           'tercer_puesto' => $fase->where('numPartido', 5)->values(),
        ])
    );
}

    public function getEliminatoriasBySubcategoria($subcategoriaId)
{
    $eliminatorias = Eliminatoria::with('equipoAa', 'equipoB')
        ->where('subcategoria_id', $subcategoriaId)
        ->get();

    // Esto crea dinámicamente "Principal", "Copa de Plata", etc.
    $resultado = $eliminatorias->groupBy(fn($q) => $q->nombre_fase ?? 'Principal')
        ->map(function($grupo) {
            return [
                'octavos' => $grupo->where('numPartido', 1)->values(),
                'cuartos' => $grupo->where('numPartido', 2)->values(),
                'semis'   => $grupo->where('numPartido', 3)->values(),
                'final'   => $grupo->where('numPartido', 4)->values(),
               'tercer_puesto' => $grupo->where('numPartido', 5)->values(),
            ];
        });

    return response()->json($resultado);
}


    

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        {
            $request->validate([
                'equipo_a_id' => 'nullable|exists:equipos,id',
                'equipo_b_id' => 'nullable|exists:equipos,id',
                'marcador1_ida' => 'nullable|integer',
                'marcador2_ida' => 'nullable|integer',
                'marcador1_vuelta' => 'nullable|integer',
                'marcador2_vuelta' => 'nullable|integer',
                'marcador1_penales' => 'nullable|integer',
                'marcador2_penales' => 'nullable|integer',
                'nombre_fase' => 'required|string',
                'numPartido' => 'required|integer',
                'subcategoria_id' => 'required|integer' ,
             'tipo_eliminatoria' => 'required|in:solo_ida,ida_vuelta,penales',
             
                
            ]);
    
            $eliminatoria = Eliminatoria::create($request->all());
    
            return response()->json($eliminatoria, 201);
        }
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
        // Validar los datos de entrada
        $request->validate([
            'equipo_a_id' => 'nullable|exists:equipos,id',
            'equipo_b_id' => 'nullable|exists:equipos,id',
            'marcador1_ida' => 'nullable|integer',
            'marcador2_ida' => 'nullable|integer',
            'marcador1_vuelta' => 'nullable|integer',
            'marcador2_vuelta' => 'nullable|integer',
            'marcador1_penales' => 'nullable|integer',
            'marcador2_penales' => 'nullable|integer',
            'numPartido' => 'required|integer',
             'nombre_fase' => 'nullable|string',
            'subcategoria_id' => 'required|integer',
            'tipo_eliminatoria' => 'required|in:solo_ida,ida_vuelta,penales',
            'tipo_partido' => 'nullable|in:ida,vuelta'
        ]);
    
        // Buscar la eliminatoria por su id
        $eliminatoria = Eliminatoria::find($id);
    
        // Verificar si la eliminatoria existe
        if (!$eliminatoria) {
            return response()->json(['message' => 'Eliminatoria no encontrada'], 404);
        }
    
        // Asignar los nuevos valores del request a la eliminatoria
        $eliminatoria->equipo_a_id = $request->equipo_a_id;
        $eliminatoria->equipo_b_id = $request->equipo_b_id;
        $eliminatoria->marcador1_ida = $request->marcador1_ida;
        $eliminatoria->marcador2_ida = $request->marcador2_ida;
        $eliminatoria->marcador1_vuelta = $request->marcador1_vuelta;
        $eliminatoria->marcador2_vuelta = $request->marcador2_vuelta;
        $eliminatoria->marcador1_penales = $request->marcador1_penales;
        $eliminatoria->marcador2_penales = $request->marcador2_penales;
        $eliminatoria->numPartido = $request->numPartido;
        $eliminatoria->subcategoria_id = $request->subcategoria_id;
        $eliminatoria->tipo_eliminatoria = $request->tipo_eliminatoria;
        $eliminatoria->tipo_partido = $request->tipo_partido;
    
        // Guardar los cambios
        $eliminatoria->save();
        
    
        // Responder con éxito
        return response()->json(['message' => 'Eliminatoria actualizada correctamente', 'eliminatoria' => $eliminatoria], 200);
    }
    

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
         $eliminatoria = Eliminatoria::findOrFail($id);  
        $eliminatoria->delete();
        return response()->json(['message' => 'eliminatoria eliminada correctamente']);
    }
}
