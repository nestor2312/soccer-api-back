<?php

namespace App\Observers;

use App\Models\EventoPartido;
use App\Models\Player;

class EventoPartidoObserver
{
    public function created(EventoPartido $evento)
    {
        $this->sumar($evento);
    }

    public function deleted(EventoPartido $evento)
    {
        $this->restar($evento);
    }

    public function updated(EventoPartido $evento)
    {
        if ($evento->isDirty('tipo')) {
            $originalTipo = $evento->getOriginal('tipo');

            $this->restarTipo($evento->jugador_id, $originalTipo);
            $this->sumar($evento);
        }
    }

    private function sumar($evento)
    {
        $jugador = Player::find($evento->jugador_id);

        if (!$jugador) return;

        match ($evento->tipo) {
            'gol' => $jugador->increment('goles'),
            'asistencia' => $jugador->increment('asistencias'),
            'amarilla' => $jugador->increment('card_amarilla'),
            'roja' => $jugador->increment('card_roja'),
        };
    }

    private function restar($evento)
    {
        $this->restarTipo($evento->jugador_id, $evento->tipo);
    }

    private function restarTipo($jugadorId, $tipo)
    {
        $jugador = Player::find($jugadorId);

        if (!$jugador) return;

        match ($tipo) {
            'gol' => $jugador->decrement('goles'),
            'asistencia' => $jugador->decrement('asistencias'),
            'amarilla' => $jugador->decrement('card_amarilla'),
            'roja' => $jugador->decrement('card_roja'),
        };
    }
}
