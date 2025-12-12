<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\User; // <-- asegúrate de importar tu modelo
use Illuminate\Mail\Mailables\Envelope;
class WelcomeMail extends Mailable
{
    use Queueable, SerializesModels;

    public $usuario;

    /**
     * Create a new message instance.
     */
    public function __construct(User $usuario)
    {
        $this->usuario = $usuario;
    }

      public function envelope()
    {
        return new Envelope(
            subject: '¡Bienvenido a Fubol!',
        );
    }
    /**
     * Build the message.
     */
    public function build()
    {
        return $this
                    ->view('emails.welcome')
                    ->with([
                        'usuario' => $this->usuario,
                    ]);
    }
}
