<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SubCategory;
use App\Models\Partido;
use App\Models\Equipo;
use App\Models\Grupos;
use App\Models\Player;
use Illuminate\Support\Facades\DB;
class SubCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($categoriaId)
    {
        $subcategorias = SubCategory::where('categoria_id', $categoriaId)->get();
    return response()->json($subcategorias);
    }

    public function indexPorSubcategoria($subcategoriaId)
    {
        $partidos = Partido::with('equipoA','equipoB')
                           ->whereHas('grupo', function ($query) use ($subcategoriaId) {
                               $query->where('subcategotia_id', $subcategoriaId);
                           })
                           ->get();

        return response()->json($partidos);
    }

    public function equiposPorSubcategoria($subcategoriaId)
{
    $equipos = Equipo::whereHas('grupo', function ($query) use ($subcategoriaId) {
        $query->where('subcategoria_id', $subcategoriaId);
    })->get();

    return response()->json($equipos);
}


// public function jugadoresPorSubcategoria($subcategoriaId)
// {
//     $Jugadores = Player::whereHas('equipo', function ($query) use ($subcategoriaId) {
//         $query->where('equipos.subcategoria_id', $subcategoriaId);
//     })->get();

//     return response()->json($Jugadores);
// }

// public function jugadoresPorSubcategoria($subcategoriaId)
// {
//     // Obtener jugadores de los equipos que pertenecen a la subcategoría
//     $jugadores = Player::whereHas('equipo', function ($query) use ($subcategoriaId) {
//         // Filtrar los equipos que pertenecen a la subcategoría
//         $query->whereHas('grupo', function ($query) use ($subcategoriaId) {
//             $query->where('subcategoria_id', $subcategoriaId);
//         });
//     })->get();

//     return response()->json($jugadores);
// }
public function jugadoresPorSubcategoria($subcategoriaId)
{
    // Obtener los jugadores de los equipos pertenecientes a la subcategoría
    $jugadores = Player::whereHas('equipo.grupo', function ($query) use ($subcategoriaId) {
        $query->where('subcategoria_id', $subcategoriaId);
    })
    ->with('equipo')->get();

    return response()->json($jugadores);
}

public function jugadoresPorSubcategoriaPaginador($subcategoriaId)
{
    // Obtener los jugadores de los equipos pertenecientes a la subcategoría
    $jugadores = Player::whereHas('equipo.grupo', function ($query) use ($subcategoriaId) {
        $query->where('subcategoria_id', $subcategoriaId);
    })
    ->with('equipo')->paginate(9);

    return response()->json($jugadores);
}

public function partidosPorSubcategoria($subcategoriaId)
{
    $partidos = Partido::whereHas('equipoA.grupo', function ($query) use ($subcategoriaId) {
        $query->where('subcategoria_id', $subcategoriaId);
    })
    ->orWhereHas('equipoB.grupo', function ($query) use ($subcategoriaId) {
        $query->where('subcategoria_id', $subcategoriaId);
    })
    ->with(['equipoA', 'equipoB'])->get();

    return response()->json($partidos);
}


public function partidosPorSubcategoriaPaginador($subcategoriaId)
{
    $partidos = Partido::whereHas('equipoA.grupo', function ($query) use ($subcategoriaId) {
        $query->where('subcategoria_id', $subcategoriaId);
    })
    ->orWhereHas('equipoB.grupo', function ($query) use ($subcategoriaId) {
        $query->where('subcategoria_id', $subcategoriaId);
    })
    ->with(['equipoA', 'equipoB'])->paginate(9);

    return response()->json($partidos);
}



public function ClasificacionPorSubcategoria($subcategoriaId) 
 {
    
    // Obtener los grupos que pertenecen a la subcategoría
    // clasificacion usuario paginador
    $grupos = Grupos::where('subcategoria_id', $subcategoriaId)
                    ->orderBy('nombre', 'asc')->paginate(4);
    $datosGrupos = [];
    // Recorrer los grupos y obtener los equipos con sus estadísticas
    foreach ($grupos as $grupo) {
        // Consulta para obtener los equipos de cada grupo
        $equipos = DB::select('
            SELECT e.nombre, e.archivo,
                   SUM(CASE WHEN u.GF > u.GA THEN 3 ELSE 0 END + CASE WHEN u.GF = u.GA THEN 1 ELSE 0 END) puntos,
                   COUNT(CASE WHEN u.GF > u.GA THEN 1 END) pg,
                   COUNT(CASE WHEN u.GF < u.GA THEN 1 END) pp,
                   COUNT(CASE WHEN u.GF = u.GA THEN 1 END) pe,
                   COUNT(CASE WHEN u.GF > u.GA THEN 1 END) + COUNT(CASE WHEN u.GF < u.GA THEN 1 END) + COUNT(CASE WHEN u.GF = u.GA THEN 1 END) pj,
                   SUM(u.GF) AS gf,
                   SUM(u.GA) AS gc,
                   SUM(u.GF - u.GA) AS gd
            FROM (
                SELECT p.equipoA_id as team_id, p.marcador1 as GF, p.marcador2 as GA FROM partidos p
                UNION ALL
                SELECT p.equipoB_id as team_id, p.marcador2 as GF, p.marcador1 as GA FROM partidos p
            ) u 
            INNER JOIN equipos e 
            ON u.team_id = e.id 
            WHERE e.grupo_id = :grupo_id
            GROUP BY e.id 
            ORDER BY puntos DESC', 
            ['grupo_id' => $grupo->id]);
        // Añadir los datos del grupo con sus equipos
        $datosGrupos[] = [
            'grupo' => $grupo,
            'equipos' => $equipos
        ];
    }

    return response()->json([
        'data' => $datosGrupos,
        'current_page' => $grupos->currentPage(),
        'last_page' => $grupos->lastPage(),
        'total' => $grupos->total()
    ]);
}


public function ClasificacionInicioPorSubcategoria($subcategoriaId) 
 {
    
    // Obtener los grupos que pertenecen a la subcategoría
    $grupos = Grupos::where('subcategoria_id', $subcategoriaId)
                    ->orderBy('nombre', 'asc')->paginate(4);
    $datosGrupos = [];
    // Recorrer los grupos y obtener los equipos con sus estadísticas
    foreach ($grupos as $grupo) {
        // Consulta para obtener los equipos de cada grupo
        $equipos = DB::select('
            SELECT e.nombre, e.archivo,
                   SUM(CASE WHEN u.GF > u.GA THEN 3 ELSE 0 END + CASE WHEN u.GF = u.GA THEN 1 ELSE 0 END) puntos,
                   COUNT(CASE WHEN u.GF > u.GA THEN 1 END) pg,
                   COUNT(CASE WHEN u.GF < u.GA THEN 1 END) pp,
                   COUNT(CASE WHEN u.GF = u.GA THEN 1 END) pe,
                   COUNT(CASE WHEN u.GF > u.GA THEN 1 END) + COUNT(CASE WHEN u.GF < u.GA THEN 1 END) + COUNT(CASE WHEN u.GF = u.GA THEN 1 END) pj,
                   SUM(u.GF) AS gf,
                   SUM(u.GA) AS gc,
                   SUM(u.GF - u.GA) AS gd
            FROM (
                SELECT p.equipoA_id as team_id, p.marcador1 as GF, p.marcador2 as GA FROM partidos p
                UNION ALL
                SELECT p.equipoB_id as team_id, p.marcador2 as GF, p.marcador1 as GA FROM partidos p
            ) u 
            INNER JOIN equipos e 
            ON u.team_id = e.id 
            WHERE e.grupo_id = :grupo_id
            GROUP BY e.id 
            ORDER BY puntos DESC', 
            ['grupo_id' => $grupo->id]);
        // Añadir los datos del grupo con sus equipos
        $datosGrupos[] = [
            'grupo' => $grupo,
            'equipos' => $equipos
        ];
    }

    return $datosGrupos;
}

public function Admin()
{
    $subcategorias = SubCategory::with('categoria')->orderBy('id', 'desc')->get();
    // $categorias = Category::where('torneo_id', $torneoId)->get();
    return response()->json($subcategorias);
}

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $subcategoria = new SubCategory();
        $subcategoria -> categoria_id = $request->get('categoria_id');
        $subcategoria -> nombre = $request->get('nombre');
        
        $subcategoria ->save();
        return response()->json($subcategoria, 201);
    }
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($subcategoriaId)
    {
        $subcategoria = SubCategory::with('grupos.equipos')
                        ->find($subcategoriaId);
        return response()->json($subcategoria);
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
        $subcategoria = SubCategory::find($id);

        // Verificar si la subcategoria existe
        if (!$subcategoria) {
            return response()->json(['message' => 'subcategoria no encontrada'], 404);
        }
    
        // Actualizar y guardar
        $subcategoria->nombre = $request->nombre;
        $subcategoria -> categoria_id = $request->categoria_id;
        $subcategoria->save();
    
        // Retornar la respuesta
        return response()->json($subcategoria, 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    
    {
        $subcategoria = SubCategory::findOrFail($id);  
        $subcategoria->delete();
        return response()->json(['message' => 'subcategoria eliminada correctamente']);
    }
}
