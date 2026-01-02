<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Mail\Mailables\Envelope;
class EndDemoMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $usuario;

    /**
     * Crea una nueva instancia del mensaje.
     */
    public function __construct(User $usuario)
    {
        $this->usuario = $usuario;
    }

      public function envelope()
    {
        return new Envelope(
            subject: '¡Tu periodo de prueba está por finalizar!',
        );
    }

    /**
     * Construye el mensaje.
     */
    public function build()
    {
        $fechaFin = Carbon::now()
            ->addDays(3)
            ->locale('es')
            ->isoFormat('D [de] MMMM [de] YYYY');

        return $this
                    ->view('emails.EndDemo')
                    ->with([
                        'usuario' => $this->usuario,
                        'fechaFin' => $fechaFin,
                    ]);
    }
}
