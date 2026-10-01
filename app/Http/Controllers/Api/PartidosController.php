<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Partido;

class PartidosController extends Controller
{
    public function index(Request $request)
    {
        $query = Partido::with([
            'equipoA.grupos.subcategoria.categoria.torneo',
            'equipoB.grupos.subcategoria.categoria.torneo'
        ]);

        if ($request->filled('torneo_id')) {
            $torneoId = $request->torneo_id;

            $query->where(function ($q) use ($torneoId) {
                $q->whereHas('equipoA.grupos.subcategoria.categoria', function ($subQ) use ($torneoId) {
                    $subQ->where('torneo_id', $torneoId);
                })->orWhereHas('equipoB.grupos.subcategoria.categoria', function ($subQ) use ($torneoId) {
                    $subQ->where('torneo_id', $torneoId);
                });
            });
        }

        if ($request->filled('categoria_id')) {
            $categoriaId = $request->categoria_id;

            $query->where(function ($q) use ($categoriaId) {
                $q->whereHas('equipoA.grupos.subcategoria', function ($subQ) use ($categoriaId) {
                    $subQ->where('categoria_id', $categoriaId);
                })->orWhereHas('equipoB.grupos.subcategoria', function ($subQ) use ($categoriaId) {
                    $subQ->where('categoria_id', $categoriaId);
                });
            });
        }

        if ($request->filled('subcategoria_id')) {
            $subcategoriaId = $request->subcategoria_id;

            $query->where(function ($q) use ($subcategoriaId) {
                $q->whereHas('equipoA.grupos', function ($subQ) use ($subcategoriaId) {
                    $subQ->where('subcategoria_id', $subcategoriaId);
                })->orWhereHas('equipoB.grupos', function ($subQ) use ($subcategoriaId) {
                    $subQ->where('subcategoria_id', $subcategoriaId);
                });
            });
        }

        if ($request->filled('jornada')) {
            $query->where('jornada', $request->jornada);
        }

        return $query->orderBy('id', 'desc')->paginate(10);
    }

    public function show($id)
    {
        return Partido::with([
            'equipoA.grupos.subcategoria.categoria.torneo',
            'equipoB.grupos.subcategoria.categoria.torneo'
        ])->findOrFail($id);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'equipoA_id' => 'required|exists:equipos,id',
            'equipoB_id' => 'required|exists:equipos,id',
            'marcador1' => 'nullable|integer',
            'marcador2' => 'nullable|integer',
             'grupo_id'   => 'required|exists:grupos,id',
            'fecha' => 'nullable|date',
            'hora' => 'nullable',
            'sede' => 'nullable',
            'jornada' => 'nullable'
        ]);

        $partido = Partido::create($data);

        return $partido->load([
            'equipoA.grupos.subcategoria.categoria.torneo',
            'equipoB.grupos.subcategoria.categoria.torneo'
        ]);
    }

    public function update(Request $request, $id)
    {
        $partido = Partido::findOrFail($id);

        $data = $request->validate([
            'equipoA_id' => 'required|exists:equipos,id',
            'equipoB_id' => 'required|exists:equipos,id',
            'marcador1' => 'nullable|integer',
            'marcador2' => 'nullable|integer',
             'grupo_id'   => 'required|exists:grupos,id',
            'fecha' => 'nullable',
            'hora' => 'nullable',
            'sede' => 'nullable',
            'jornada' => 'nullable'
        ]);

        $partido->update($data);

        return $partido->load([
            'equipoA.grupos.subcategoria.categoria.torneo',
            'equipoB.grupos.subcategoria.categoria.torneo'
        ]);
    }

    public function destroy($id)
    {
        Partido::findOrFail($id)->delete();

        return response()->json(['message' => 'Partido eliminado correctamente']);
    }
}