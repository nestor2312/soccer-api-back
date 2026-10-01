<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Equipo;
use App\Models\Grupos;
use Illuminate\Support\Facades\DB;
class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function Teams()
    {
        $equipos = Equipo::with('grupo')
        ->orderBy('nombre', 'asc') 
        ->paginate(18); 
        return $equipos;
    }
    public function HomeTeams()
    {
        $equipos = Equipo::with('grupo')->get();
      
        return $equipos;
    }


    // panel admin clasificacion
    // public function Homeclassification(Request $request)
    // {
    //     $page = $request->get('pages', 4);
    // $grupos = Grupos::orderBy('nombre', 'asc')->paginate($page);
    
    // $datosGrupos = [];
    // foreach ($grupos as $grupo) {
    //     $equipos = DB::select('SELECT e.`nombre`, e.`archivo`,
    //         SUM(CASE WHEN u.GF > u.GA THEN 3 ELSE 0 END + CASE WHEN u.GF = u.GA THEN 1 ELSE 0 END) puntos,
    //         COUNT(CASE WHEN u.GF > u.GA THEN 1 END) pg,
    //         COUNT(CASE WHEN u.GF < u.GA THEN 1 END) pp,
    //         COUNT(CASE WHEN u.GF = u.GA THEN 1 END) pe,
    //         COUNT(CASE WHEN u.GF > u.GA THEN 1 END) + COUNT(CASE WHEN u.GF < u.GA THEN 1 END) + COUNT(CASE WHEN u.GF = u.GA THEN 1 END) pj,
    //         SUM(u.GF) AS "gf",
    //         SUM(u.GA) AS "gc",
    //         SUM(u.GF - u.GA) AS "gd"
    //         FROM (
    //             SELECT p.equipoA_id as team_id, p.marcador1 as GF, p.marcador2 as GA FROM partidos p
    //             UNION ALL
    //             SELECT p.equipoB_id as team_id, p.marcador2 as GF, p.marcador1 as GA FROM partidos p
    //         ) u 
    //         INNER JOIN equipos e 
    //         ON u.team_id = e.id 
    //         WHERE grupo_id = :id
    //         GROUP BY e.id 
    //         ORDER BY puntos DESC', ['id' => $grupo->id]);

    //     $datosGrupos[] = ['grupo' => $grupo, 'equipos' => $equipos];
    // }
    
    // return [
    //     'data' => $datosGrupos,
    //     'current_page' => $grupos->currentPage(),
    //     'last_page' => $grupos->lastPage(),
    // ];
    // }


public function HomeclassificationAll(Request $request)
{
    $Page = $request->get('pages', 2);

    $grupos = Grupos::with('subcategoria.categoria.torneo')
        ->orderBy('nombre', 'asc')
        ->paginate($Page);

    $datosGrupos = [];

    foreach ($grupos as $grupo) {
 $equipos = DB::select("
SELECT 
    e.id,
    e.nombre,
    e.archivo,

    COALESCE(SUM(
        CASE 
            WHEN u.GF > u.GA THEN 3
            WHEN u.GF = u.GA THEN 1
            ELSE 0
        END
    ), 0) AS puntos,

    COALESCE(SUM(CASE WHEN u.GF > u.GA THEN 1 ELSE 0 END), 0) AS pg,
    COALESCE(SUM(CASE WHEN u.GF < u.GA THEN 1 ELSE 0 END), 0) AS pp,
    COALESCE(SUM(CASE WHEN u.GF = u.GA THEN 1 ELSE 0 END), 0) AS pe,
    COALESCE(COUNT(u.team_id), 0) AS pj,
    COALESCE(SUM(u.GF), 0) AS gf,
    COALESCE(SUM(u.GA), 0) AS gc,
    COALESCE(SUM(u.GF - u.GA), 0) AS gd

FROM equipos e

LEFT JOIN (
    SELECT 
        p.equipoA_id AS team_id,
        p.marcador1 AS GF,
        p.marcador2 AS GA
    FROM partidos p
    WHERE p.grupo_id = ?
    AND p.marcador1 IS NOT NULL 
    AND p.marcador2 IS NOT NULL

    UNION ALL

    SELECT 
        p.equipoB_id AS team_id,
        p.marcador2 AS GF,
        p.marcador1 AS GA
    FROM partidos p
    WHERE p.grupo_id = ?
    AND p.marcador1 IS NOT NULL 
    AND p.marcador2 IS NOT NULL
) u ON u.team_id = e.id

WHERE EXISTS (
    SELECT 1 
    FROM grupo_equipo ge 
    WHERE ge.equipo_id = e.id 
    AND ge.grupo_id = ?
)

GROUP BY e.id, e.nombre, e.archivo
ORDER BY puntos DESC, gd DESC, gf DESC
", [
    $grupo->id,
    $grupo->id,
    $grupo->id
]);
        $datosGrupos[] = [
            'grupo' => [
                'id' => $grupo->id,
                'nombre' => $grupo->nombre,
                'num_clasificados' => $grupo->num_clasificados,
                'subcategoria' => [
                    'id' => $grupo->subcategoria->id ?? null,
                    'nombre' => $grupo->subcategoria->nombre ?? null,
                    'categoria' => [
                        'id' => $grupo->subcategoria->categoria->id ?? null,
                        'nombre' => $grupo->subcategoria->categoria->nombre ?? null,
                        'torneo' => [
                            'id' => $grupo->subcategoria->categoria->torneo->id ?? null,
                            'nombre' => $grupo->subcategoria->categoria->torneo->nombre ?? null,
                        ]
                    ]
                ]
            ],
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




 public function Homeclassification(Request $request, $subcategoriaId)
{
    $Page = $request->get('pages', 2);

    // Solo grupos de esa subcategoría
    $grupos = Grupos::with('subcategoria.categoria.torneo')
        ->where('subcategoria_id', $subcategoriaId)
        ->orderBy('nombre', 'asc')
        ->paginate($Page);

    $datosGrupos = [];

    foreach ($grupos as $grupo) {
       $equipos = DB::select('
SELECT 
    e.id,
    e.nombre,
    e.archivo,

    COALESCE(SUM(
        CASE 
            WHEN u.GF > u.GA THEN 3
            WHEN u.GF = u.GA THEN 1
            ELSE 0
        END
    ), 0) AS puntos,

    COALESCE(SUM(CASE WHEN u.GF > u.GA THEN 1 ELSE 0 END), 0) AS pg,
    COALESCE(SUM(CASE WHEN u.GF < u.GA THEN 1 ELSE 0 END), 0) AS pp,
    COALESCE(SUM(CASE WHEN u.GF = u.GA THEN 1 ELSE 0 END), 0) AS pe,
    COALESCE(COUNT(u.team_id), 0) AS pj,
    COALESCE(SUM(u.GF), 0) AS gf,
    COALESCE(SUM(u.GA), 0) AS gc,
    COALESCE(SUM(u.GF - u.GA), 0) AS gd

FROM equipos e

LEFT JOIN (
    SELECT 
        p.equipoA_id AS team_id,
        p.marcador1 AS GF,
        p.marcador2 AS GA
    FROM partidos p
    WHERE p.grupo_id = :grupo_id
    AND p.marcador1 IS NOT NULL AND p.marcador2 IS NOT NULL

    UNION ALL

    SELECT 
        p.equipoB_id AS team_id,
        p.marcador2 AS GF,
        p.marcador1 AS GA
    FROM partidos p
    WHERE p.grupo_id = :grupo_id
    AND p.marcador1 IS NOT NULL AND p.marcador2 IS NOT NULL
) u ON u.team_id = e.id

WHERE EXISTS (
    SELECT 1 
    FROM grupo_equipo ge 
    WHERE ge.equipo_id = e.id 
    AND ge.grupo_id = :grupo_id
)

GROUP BY e.id, e.nombre, e.archivo
ORDER BY puntos DESC, gd DESC, gf DESC
', ['grupo_id' => $grupo->id]);

        $datosGrupos[] = [
            'grupo' => [
                'id' => $grupo->id,
                'nombre' => $grupo->nombre,
                'num_clasificados' => $grupo->num_clasificados,
                'subcategoria' => [
                    'id' => $grupo->subcategoria->id ?? null,
                    'nombre' => $grupo->subcategoria->nombre ?? null,
                    'categoria' => [
                        'id' => $grupo->subcategoria->categoria->id ?? null,
                        'nombre' => $grupo->subcategoria->categoria->nombre ?? null,
                        'torneo' => [
                            'id' => $grupo->subcategoria->categoria->torneo->id ?? null,
                            'nombre' => $grupo->subcategoria->categoria->torneo->nombre ?? null,
                        ]
                    ]
                ]
            ],
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



}
