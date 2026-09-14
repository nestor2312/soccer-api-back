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
            'dieciseisavos' => $fase->where('numPartido', 6)->values(),
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
                'dieciseisavos' => $grupo->where('numPartido', 6)->values(),
            ];
        });

    return response()->json($resultado);
}

private function limitesPlayIn() {
    return [
        1 => 4, // octavos
        2 => 2, // cuartos
        3 => 1, // semis (no permitido)
        4 => 0, // final (no permitido)
        5 => 0, // tercer puesto
        6 => 8, // dieciseisavos
    ];
}
    

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
$request->merge([
    'tipo_partido_extra' => $request->tipo_partido_extra ?? 'normal'
]);
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
                'fecha' => 'nullable|string',
                'hora' => 'nullable|string',
                'sede' => 'nullable|string',
                'nombre_fase' => 'required|string',
                'numPartido' => 'required|integer',
                'subcategoria_id' => 'required|integer',
                'tipo_partido_extra' => 'nullable|in:normal,play_in',
                'tipo_partido' => 'nullable|in:ida,vuelta',
             'tipo_eliminatoria' => 'required|in:solo_ida,ida_vuelta,penales',
             
                
            ]);

             $limites = $this->limitesPlayIn();

if ($request->tipo_partido_extra === 'play_in') {

    $max = $limites[$request->numPartido] ?? 0;

    if ($max === 0) {
        return response()->json([
            'error' => 'No se permite play-in en esta fase'
        ], 422);
    }

    $cantidadActual = Eliminatoria::where('subcategoria_id', $request->subcategoria_id)
        ->where('numPartido', $request->numPartido)
        ->where('tipo_partido_extra', 'play_in')
        ->count();

    if ($cantidadActual >= $max) {
        return response()->json([
            'error' => "Solo se permiten {$max} partidos play-in en esta fase"
        ], 422);
    }
}

    
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

    $request->merge([
    'tipo_partido_extra' => $request->tipo_partido_extra ?? 'normal'
]);
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
             'fecha' => 'nullable|string',
                'hora' => 'nullable|string',
                'sede' => 'nullable|string',
            'numPartido' => 'required|integer',
             'nombre_fase' => 'nullable|string',
            'subcategoria_id' => 'required|integer',
            'tipo_eliminatoria' => 'required|in:solo_ida,ida_vuelta,penales',
            'tipo_partido' => 'nullable|in:ida,vuelta',
            'tipo_partido_extra' => 'nullable|in:normal,play_in',
        ]);

        $limites = $this->limitesPlayIn();

    if ($request->tipo_partido_extra === 'play_in') {

    $max = $limites[$request->numPartido] ?? 0;

    if ($max === 0) {
        return response()->json([
            'error' => 'No se permite play-in en esta fase'
        ], 422);
    }

    $cantidadActual = Eliminatoria::where('subcategoria_id', $request->subcategoria_id)
        ->where('numPartido', $request->numPartido)
        ->where('tipo_partido_extra', 'play_in')
        ->where('id', '!=', $id)
        ->count();

    if ($cantidadActual >= $max) {
        return response()->json([
            'error' => "Límite de play-in alcanzado"
        ], 422);
    }
}
    
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
          $eliminatoria->fecha = $request->fecha;
            $eliminatoria->hora = $request->hora;
              $eliminatoria->sede = $request->sede;
        $eliminatoria->subcategoria_id = $request->subcategoria_id;
        $eliminatoria->tipo_eliminatoria = $request->tipo_eliminatoria;
        $eliminatoria->tipo_partido = $request->tipo_partido;
     $eliminatoria->tipo_partido_extra = $request->tipo_partido_extra;
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
