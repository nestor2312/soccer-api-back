<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;
class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function categoriaxtorneo($torneoId)
    {
        $categorias = Category::where('torneo_id', $torneoId)->get();
        return response()->json($categorias);
    }

    public function index($torneoId)
    {
        $categorias = Category::where('torneo_id', $torneoId)->get();
        return response()->json($categorias);
    }

  

    public function Admin()
    {
        $categorias = Category::with('torneo')->orderBy('id', 'desc')->get();
        // $categorias = Category::where('torneo_id', $torneoId)->get();
        return response()->json($categorias);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $categoria = new Category();
        $categoria -> torneo_id = $request->get('torneo_id');
        $categoria -> nombre = $request->get('nombre');
        
        $categoria ->save();
        return response()->json($categoria, 201);
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

        $categoria = Category::find($id);

        // Verificar si el torneo existe
        if (!$categoria) {
            return response()->json(['message' => 'categoria no encontrada'], 404);
        }
    
        // Actualizar y guardar
        $categoria->nombre = $request->nombre;
        $categoria -> torneo_id = $request->torneo_id;
        $categoria->save();
    
        // Retornar la respuesta
        return response()->json($categoria, 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $categoria = Category::findOrFail($id);  
        $categoria->delete();
        return response()->json(['message' => 'categoria eliminada correctamente']);
    }
}
