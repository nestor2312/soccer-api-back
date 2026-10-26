<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\CambioMarcadorPendiente;
use App\Models\Partido;
use Illuminate\Support\Facades\Http;

class EnviarNotificacionesMarcador extends Command
{
   protected $signature = 'app:enviar-notificaciones-marcador';

    protected $description = 'Agrupa y envía notificaciones de cambios de marcador';

    public function handle()
    {
        $pendientes = CambioMarcadorPendiente::where('notificado', false)
            ->orderBy('subcategoria_id')
            ->get()
            ->groupBy('subcategoria_id');

        if ($pendientes->isEmpty()) {
            $this->info('No hay cambios de marcador pendientes.');
            return self::SUCCESS;
        }

        foreach ($pendientes as $subcategoriaId => $cambios) {
            $ultimoCambio = $cambios->max('updated_at');

            // Esperar 2 minutos desde el último cambio.
            if (!$ultimoCambio || $ultimoCambio->gt(now()->subMinutes(2))) {
                $this->info(
                    "La subcategoría {$subcategoriaId} todavía está acumulando cambios."
                );
                continue;
            }

            $partidoIds = $cambios->pluck('partido_id')->unique();

            $partidos = Partido::with(['equipoA', 'equipoB'])
                ->whereIn('id', $partidoIds)
                ->get();

            if ($partidos->isEmpty()) {
                $cambios->each->update(['notificado' => true]);
                continue;
            }

            $detalles = [];

            foreach ($partidos as $partido) {
                $equipoA = $partido->equipoA->nombre ?? 'Equipo local';
                $equipoB = $partido->equipoB->nombre ?? 'Equipo visitante';
                $marcador1 = $partido->marcador1 ?? 0;
                $marcador2 = $partido->marcador2 ?? 0;

                $detalles[] = "{$equipoA} {$marcador1}-{$marcador2} {$equipoB}";
            }

            $mensajeEs = 'Se actualizaron ' . $partidos->count()
                . ' marcadores. ' . implode(' | ', $detalles)
                . '. Consulta los resultados en Fubolzona.';

            $mensajeEn = $partidos->count()
                . ' match scores have been updated. Check the results on Fubolzona.';

            $response = Http::withHeaders([
                'Authorization' => 'Key ' . config('services.onesignal.rest_api_key'),
            ])->post('https://api.onesignal.com/notifications', [
                'app_id' => config('services.onesignal.app_id'),
                'target_channel' => 'push',
                'headings' => [
                    'es' => '⚽ Resultados actualizados',
                    'en' => '⚽ Scores updated',
                ],
                'contents' => [
                    'es' => $mensajeEs,
                    'en' => $mensajeEn,
                ],
                'included_segments' => ['All'],
            ]);

            if ($response->successful()) {
                CambioMarcadorPendiente::whereIn(
                    'id',
                    $cambios->pluck('id')
                )->update(['notificado' => true]);

                $this->info(
                    "Notificación enviada para la subcategoría {$subcategoriaId}."
                );
            } else {
                $this->error(
                    "Error de OneSignal para la subcategoría {$subcategoriaId}: "
                    . $response->body()
                );
            }
        }

        return self::SUCCESS;
    }
}
