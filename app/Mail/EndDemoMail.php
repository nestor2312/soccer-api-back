<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Mail\Mailables\Address;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Mail\Mailables\Envelope;

// Quitamos "implements ShouldQueue" para pruebas inmediatas
class EndDemoMail extends Mailable
{
    use Queueable, SerializesModels;

    public $usuario;

    public function __construct(User $usuario)
    {
        $this->usuario = $usuario;
    }

    public function envelope()
    {
        return new Envelope(
            // Comenta o ajusta la dirección si no está verificada en Resend
            from: new Address('contacto@demo.fubolzona.com', 'Fubol'), 
            subject: '¡Tu periodo de prueba está por finalizar!',
        );
    }

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