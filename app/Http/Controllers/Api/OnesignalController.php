<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Eliminatoria;
use App\Models\Partido;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
class OnesignalController extends Controller
{
     public function enviarPrueba()
    {
       
$response = Http::withHeaders([
    'Authorization' => 'Key ' . config('services.onesignal.rest_api_key'),
    ])->post('https://api.onesignal.com/notifications', [
        'app_id' => config('services.onesignal.app_id'),
        'target_channel' => 'push',
        'headings' => [
            'en' => '⚽ Fubol',
            ],
            'contents' => [
                'en' => '¡Notificación de prueba enviada desde Laravel!',
                ],
                'included_segments' => [
                  'All',
                    ],
                    ]);
                    
                    return response()->json([
                        'status' => $response->status(),
   
    'response' => $response->json(),
]);

    }

public function notificarCambioEliminatoria(Request $request)
{
    $datos = $request->validate([
        'eliminatoria_id' => 'required|exists:eliminatorias,id',
        'cambio_fecha' => 'required|boolean',
        'cambio_hora' => 'required|boolean',
        'cambio_sede' => 'required|boolean',
        'fecha' => 'nullable|string',
        'hora' => 'nullable|string|max:10',
        'sede' => 'nullable|string|max:255',
    ]);

    // Evitar notificaciones si no cambió la programación
    if (
        !$datos['cambio_fecha'] &&
        !$datos['cambio_hora'] &&
        !$datos['cambio_sede']
    ) {
        return response()->json([
            'success' => true,
            'message' => 'No hay cambios que notificar.',
        ]);
    }

    $eliminatoria = Eliminatoria::with([
        'equipoAa',
        'equipoB',
    ])->findOrFail($datos['eliminatoria_id']);

   $equipoLocal = $eliminatoria->equipoAa->nombre ?? 'Por definir';
$equipoVisitante = $eliminatoria->equipoB->nombre ?? 'Por definir';

    $detalles = [];

    $fase = $eliminatoria->nombre_fase ?? 'Principal';

$rondas = [
    1 => 'Octavos de final',
    2 => 'Cuartos de final',
    3 => 'Semifinales',
    4 => 'Final',
    5 => 'Tercer puesto',
    6 => 'Dieciseisavos de final',
];

$ronda = $rondas[$eliminatoria->numPartido] ?? 'Fase final';

    if ($datos['cambio_fecha']) {
        $detalles[] = 'Fecha: ' . ($datos['fecha'] ?? 'por definir');
    }

    if ($datos['cambio_hora']) {
        $detalles[] = 'Hora: ' . ($datos['hora'] ?? 'por definir');
    }

    if ($datos['cambio_sede']) {
        $detalles[] = 'Sede: ' . ($datos['sede'] ?: 'por definir');
    }

    $detalleTexto = implode(' | ', $detalles);

    $response = Http::withHeaders([
        'Authorization' => 'Key ' .
            config('services.onesignal.rest_api_key'),
    ])->post('https://api.onesignal.com/notifications', [
        'app_id' => config('services.onesignal.app_id'),
        'target_channel' => 'push',

        'headings' => [
            'es' => '🏆 Eliminatoria actualizada',
            'en' => '🏆 Knockout match updated',
        ],

        'contents' => [
'es' => "Se actualizó la programación de {$equipoLocal} vs {$equipoVisitante}. {$fase} - {$ronda}. {$detalleTexto}. Consulta Fubolzona.",            'en' => "The schedule for {$equipoLocal} vs {$equipoVisitante} was updated. {$detalleTexto}. Check Fubolzona.",
        ],

        'included_segments' => ['All'],
    ]);

    return response()->json([
        'success' => $response->successful(),
        'status' => $response->status(),
        'response' => $response->json(),
    ], $response->successful() ? 200 : 502);
}


public function notificarCambioEquiposEliminatoria(Request $request)
{
    $datos = $request->validate([
        'eliminatoria_id' => 'required|exists:eliminatorias,id',
        'cambio_equipo_a' => 'required|boolean',
        'cambio_equipo_b' => 'required|boolean',
    ]);

    if (!$datos['cambio_equipo_a'] && !$datos['cambio_equipo_b']) {
        return response()->json([
            'success' => true,
            'message' => 'No hay cambios de equipos que notificar.',
        ]);
    }

    $eliminatoria = Eliminatoria::with([
        'equipoAa',
        'equipoB',
    ])->findOrFail($datos['eliminatoria_id']);

    $equipoA = $eliminatoria->equipoAa->nombre ?? 'Por definir';
    $equipoB = $eliminatoria->equipoB->nombre ?? 'Por definir';

    $rondas = [
        1 => 'Octavos de final',
        2 => 'Cuartos de final',
        3 => 'Semifinales',
        4 => 'Final',
        5 => 'Tercer puesto',
        6 => 'Dieciseisavos de final',
    ];

    $fase = $eliminatoria->nombre_fase ?? 'Principal';
    $ronda = $rondas[$eliminatoria->numPartido] ?? 'Fase final';

    $detalles = [];

    if ($datos['cambio_equipo_a']) {
        $detalles[] = "Equipo local: {$equipoA}";
    }

    if ($datos['cambio_equipo_b']) {
        $detalles[] = "Equipo visitante: {$equipoB}";
    }

    $detalleTexto = implode(' | ', $detalles);

    $response = Http::withHeaders([
        'Authorization' => 'Key '
            . config('services.onesignal.rest_api_key'),
    ])->post('https://api.onesignal.com/notifications', [
        'app_id' => config('services.onesignal.app_id'),
        'target_channel' => 'push',

        'headings' => [
            'es' => '🏆 ¡Equipo definido en la eliminatoria!',
            'en' => '🏆 Knockout team confirmed!',
        ],

        'contents' => [
            'es' => "{$fase} - {$ronda}: {$detalleTexto}. {$equipoA} vs {$equipoB}. Consulta Fubolzona.",
            'en' => "{$fase} - {$ronda}: {$detalleTexto}. {$equipoA} vs {$equipoB}. Check Fubolzona.",
        ],

        'included_segments' => ['All'],
    ]);

    return response()->json([
        'success' => $response->successful(),
        'status' => $response->status(),
        'response' => $response->json(),
    ], $response->successful() ? 200 : 502);
}




public function notificarMarcadorEliminatoria(Request $request)
{
    $datos = $request->validate([
        'eliminatoria_id' => 'required|exists:eliminatorias,id',
        'cambios_marcador' => 'required|array',
        'cambios_marcador.ida' => 'required|boolean',
        'cambios_marcador.vuelta' => 'required|boolean',
        'cambios_marcador.penales' => 'required|boolean',
    ]);

    $cambios = $datos['cambios_marcador'];

    if (
        !$cambios['ida'] &&
        !$cambios['vuelta'] &&
        !$cambios['penales']
    ) {
        return response()->json([
            'success' => true,
            'message' => 'No hay marcadores que notificar.',
        ]);
    }

    $eliminatoria = Eliminatoria::with([
        'equipoAa',
        'equipoB',
    ])->findOrFail($datos['eliminatoria_id']);

    $equipoLocal = $eliminatoria->equipoAa->nombre
        ?? 'Por definir';

    $equipoVisitante = $eliminatoria->equipoB->nombre
        ?? 'Por definir';

    $rondas = [
        1 => 'Octavos de final',
        2 => 'Cuartos de final',
        3 => 'Semifinales',
        4 => 'Final',
        5 => 'Tercer puesto',
        6 => 'Dieciseisavos de final',
    ];

    $fase = $eliminatoria->nombre_fase ?? 'Principal';
    $ronda = $rondas[$eliminatoria->numPartido] ?? 'Fase final';

    $marcadores = [];

    if ($cambios['ida']) {
        $marcadores[] = 'Ida: '
            . ($eliminatoria->marcador1_ida ?? '-')
            . ' - '
            . ($eliminatoria->marcador2_ida ?? '-');
    }

    if ($cambios['vuelta']) {
        $marcadores[] = 'Vuelta: '
            . ($eliminatoria->marcador1_vuelta ?? '-')
            . ' - '
            . ($eliminatoria->marcador2_vuelta ?? '-');
    }

    if ($cambios['penales']) {
        $marcadores[] = 'Penales: '
            . ($eliminatoria->marcador1_penales ?? '-')
            . ' - '
            . ($eliminatoria->marcador2_penales ?? '-');
    }

    $detalleMarcadores = implode(' | ', $marcadores);

    $response = Http::withHeaders([
        'Authorization' => 'Key '
            . config('services.onesignal.rest_api_key'),
    ])->post('https://api.onesignal.com/notifications', [
        'app_id' => config('services.onesignal.app_id'),
        'target_channel' => 'push',

        'headings' => [
            'es' => '⚽ Marcador de eliminatoria actualizado',
            'en' => '⚽ Knockout score updated',
        ],

        'contents' => [
            'es' => "{$fase} - {$ronda}: {$equipoLocal} vs {$equipoVisitante}. {$detalleMarcadores}. Consulta Fubolzona.",
            'en' => "{$fase} - {$ronda}: {$equipoLocal} vs {$equipoVisitante}. {$detalleMarcadores}. Check Fubolzona.",
        ],

        'included_segments' => ['All'],
    ]);

    return response()->json([
        'success' => $response->successful(),
        'status' => $response->status(),
        'response' => $response->json(),
    ], $response->successful() ? 200 : 502);
}


public function notificarFixture(Request $request)
{
    $datos = $request->validate([
        'nombre_grupo' => 'required|string|max:150',
        'total_partidos' => 'required|integer|min:1',
    ]);

    $response = Http::withHeaders([
        'Authorization' => 'Key ' . config('services.onesignal.rest_api_key'),
    ])->post('https://api.onesignal.com/notifications', [
        'app_id' => config('services.onesignal.app_id'),
        'target_channel' => 'push',
        'headings' => [
            'en' => '⚽ ¡Fixture listo!',
        ],
       'contents' => [
    'en' => 'Ya están programados los '
        . $datos['total_partidos']
        . ' partidos del '
        . $datos['nombre_grupo']
        . '. ¡Consulta el calendario en Fubolzona!',
],
        'included_segments' => ['All'],
    ]);

    return response()->json([
        'success' => $response->successful(),
        'status' => $response->status(),
        'response' => $response->json(),
    ], $response->successful() ? 200 : 502);
}




public function notificarcambiopartido(Request $request)
{
    $datos = $request->validate([
        'partido_id' => 'required|exists:partidos,id',
        'cambio_fecha' => 'required|boolean',
        'cambio_hora' => 'required|boolean',
        'cambio_sede' => 'required|boolean',
        'fecha' => 'nullable|date',
        'hora' => 'nullable|string|max:10',
        'sede' => 'nullable|string|max:255',
    ]);

    // Evitar notificaciones si ningún campo cambió
    if (
        !$datos['cambio_fecha'] &&
        !$datos['cambio_hora'] &&
        !$datos['cambio_sede']
    ) {
        return response()->json([
            'success' => true,
            'message' => 'No hay cambios que notificar.',
        ]);
    }

    $partido = Partido::with(['equipoA', 'equipoB'])
        ->findOrFail($datos['partido_id']);

    $equipoLocal = $partido->equipoA->nombre ?? 'Equipo Local';
    $equipoVisitante = $partido->equipoB->nombre ?? 'Equipo Visitante';

    // Construir el mensaje solo con los campos modificados
    $detalles = [];

    if ($datos['cambio_fecha']) {
        $detalles[] = 'Fecha: ' . ($datos['fecha'] ?? 'por definir');
    }

    if ($datos['cambio_hora']) {
        $detalles[] = 'Hora: ' . ($datos['hora'] ?? 'por definir');
    }

    if ($datos['cambio_sede']) {
        $detalles[] = 'Sede: ' . ($datos['sede'] ?: 'por definir');
    }

    $detalleTexto = implode(' | ', $detalles);

    $response = Http::withHeaders([
        'Authorization' => 'Key ' .
            config('services.onesignal.rest_api_key'),
    ])->post('https://api.onesignal.com/notifications', [
        'app_id' => config('services.onesignal.app_id'),
        'target_channel' => 'push',

        'headings' => [
            'es' => '⚽ Partido actualizado',
            'en' => '⚽ Match updated',
        ],

        'contents' => [
            'es' => "Se actualizaron los datos de {$equipoLocal} vs {$equipoVisitante}. {$detalleTexto}. Consulta la información en Fubolzona.",
            'en' => "Updates for {$equipoLocal} vs {$equipoVisitante}. {$detalleTexto}. Check Fubolzona for details.",
        ],

        'included_segments' => ['All'],
    ]);

    return response()->json([
        'success' => $response->successful(),
        'status' => $response->status(),
        'response' => $response->json(),
    ], $response->successful() ? 200 : 502);
}



}
