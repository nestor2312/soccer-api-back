<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Partido;

class PartidosController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Cargamos las relaciones necesarias para ambos equipos
        $query = Partido::with([
            'equipoA.grupo.subcategoria.categoria',
            'equipoB.grupo.subcategoria.categoria'
        ]);

        // Filtro por Torneo (Valida que el parámetro no esté vacío)
        if ($request->filled('torneo_id')) {
            $torneoId = $request->torneo_id;
            $query->where(function($q) use ($torneoId) {
                $q->whereHas('equipoA.grupo.subcategoria.categoria', function($subQ) use ($torneoId) {
                    $subQ->where('torneo_id', $torneoId);
                })->orWhereHas('equipoB.grupo.subcategoria.categoria', function($subQ) use ($torneoId) {
                    $subQ->where('torneo_id', $torneoId);
                });
            });
        }

        // Filtro por Categoría
        if ($request->filled('categoria_id')) {
            $categoriaId = $request->categoria_id;
            $query->where(function($q) use ($categoriaId) {
                $q->whereHas('equipoA.grupo.subcategoria', function($subQ) use ($categoriaId) {
                    $subQ->where('categoria_id', $categoriaId);
                })->orWhereHas('equipoB.grupo.subcategoria', function($subQ) use ($categoriaId) {
                    $subQ->where('categoria_id', $categoriaId);
                });
            });
        }

        // Filtro por Subcategoría
        if ($request->filled('subcategoria_id')) {
            $subcategoriaId = $request->subcategoria_id;
            $query->where(function($q) use ($subcategoriaId) {
                $q->whereHas('equipoA.grupo', function($subQ) use ($subcategoriaId) {
                    $subQ->where('subcategoria_id', $subcategoriaId);
                })->orWhereHas('equipoB.grupo', function($subQ) use ($subcategoriaId) {
                    $subQ->where('subcategoria_id', $subcategoriaId);
                });
            });
        }

        // Filtro por Jornada (Búsqueda exacta o parcial)
        if ($request->filled('jornada')) {
            $query->where('jornada', $request->jornada);
        }

        // Ordenar y paginar
        $partidos = $query->orderBy('id', 'desc')->paginate(10);

        return response()->json($partidos);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'equipoA_id' => 'required|integer|exists:equipos,id',
            'equipoB_id' => 'required|integer|exists:equipos,id',
            'marcador1'  => 'nullable|integer|min:0',
            'marcador2'  => 'nullable|integer|min:0',
            'fecha'      => 'nullable|date',
            'hora'       => 'nullable',
            'sede'       => 'nullable|string',
            'jornada'    => 'nullable',
        ]);

        $partido = Partido::create($validatedData);

        // Cargar las relaciones antes de responder para que el Frontend las reciba de inmediato
        $partido->load(['equipoA', 'equipoB']);

        return response()->json($partido, 201);
    }

    /**
     * Display the specified resource.
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
            'marcador1'  => 'nullable|integer|min:0',
            'marcador2'  => 'nullable|integer|min:0',
            'fecha'      => 'nullable',
            'hora'       => 'nullable',
            'jornada'    => 'nullable',
            'sede'       => 'nullable',
        ]);

        $partido->update($validatedData);
        $partido->load(['equipoA', 'equipoB']);

        return response()->json([
            'message' => 'Partido actualizado correctamente',
            'partido' => $partido
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $partido = Partido::findOrFail($id);
        $partido->delete();

        return response()->json(['message' => 'Partido eliminado correctamente']);
    }
}