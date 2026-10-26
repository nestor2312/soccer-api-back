<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
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
