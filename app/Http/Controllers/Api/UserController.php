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
    public function Homeclassification(Request $request)
    {
        $page = $request->get('pages', 4);
    $grupos = Grupos::orderBy('nombre', 'asc')->paginate($page);
    
    $datosGrupos = [];
    foreach ($grupos as $grupo) {
        $equipos = DB::select('SELECT e.`nombre`, e.`archivo`,
            SUM(CASE WHEN u.GF > u.GA THEN 3 ELSE 0 END + CASE WHEN u.GF = u.GA THEN 1 ELSE 0 END) puntos,
            COUNT(CASE WHEN u.GF > u.GA THEN 1 END) pg,
            COUNT(CASE WHEN u.GF < u.GA THEN 1 END) pp,
            COUNT(CASE WHEN u.GF = u.GA THEN 1 END) pe,
            COUNT(CASE WHEN u.GF > u.GA THEN 1 END) + COUNT(CASE WHEN u.GF < u.GA THEN 1 END) + COUNT(CASE WHEN u.GF = u.GA THEN 1 END) pj,
            SUM(u.GF) AS "gf",
            SUM(u.GA) AS "gc",
            SUM(u.GF - u.GA) AS "gd"
            FROM (
                SELECT p.equipoA_id as team_id, p.marcador1 as GF, p.marcador2 as GA FROM partidos p
                UNION ALL
                SELECT p.equipoB_id as team_id, p.marcador2 as GF, p.marcador1 as GA FROM partidos p
            ) u 
            INNER JOIN equipos e 
            ON u.team_id = e.id 
            WHERE grupo_id = :id
            GROUP BY e.id 
            ORDER BY puntos DESC', ['id' => $grupo->id]);

        $datosGrupos[] = ['grupo' => $grupo, 'equipos' => $equipos];
    }
    
    return [
        'data' => $datosGrupos,
        'current_page' => $grupos->currentPage(),
        'last_page' => $grupos->lastPage(),
    ];
    }
    // public function index()
    // {
    //     //
    // }

}
