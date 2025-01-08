<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Equipo;
use App\Models\Grupos;
use Illuminate\Support\Facades\DB;
class TableController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $grupos = Grupos::orderBy('nombre', 'asc')->get();
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
        return $datosGrupos;

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
