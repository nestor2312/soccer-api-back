<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Grupos;

class GruposController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

     public function gruposXsubcategoria($subcategoriaId)
     {
         $grupos = Grupos::where('subcategoria_id', $subcategoriaId)->get();
         return response()->json($grupos);
     }
     

    public function index()
    {
        $grupos = Grupos::with('subcategoria')->orderBy('id', 'desc')->get(); 
        // $grupos = Grupos::all();
        return $grupos;
    }

    public function Admin()
    {
        $grupos = Grupos::with('subcategoria')->orderBy('id', 'desc')->paginate(10); 
        // $categorias = Category::where('torneo_id', $torneoId)->get();
        return response()->json($grupos);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $grupo = new Grupos();
        $grupo->nombre = $request->nombre;
        $grupo->num_clasificados = $request->num_clasificados;
        $grupo->subcategoria_id = $request->subcategoria_id;
        
        $grupo->save();
        return response()->json($grupo, 201);
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
         // Buscar el torneo
         $grupo = Grupos::find($id);
    
         // Verificar si el torneo existe
         if (!$grupo) {
             return response()->json(['message' => 'Grupo no encontrado'], 404);
         }
     
         // Actualizar y guardar
    
         $grupo->nombre = $request->nombre;
         $grupo->num_clasificados = $request->num_clasificados;
         $grupo->subcategoria_id = $request->subcategoria_id;
         $grupo->save();
     
         // Retornar la respuesta
         return response()->json($grupo, 200); 
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $grupo = Grupos::findOrFail($id);  
        $grupo->delete();
        return response()->json(['message' => 'grupo eliminado correctamente']);
    }
}
