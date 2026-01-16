<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use App\Models\User;
use Illuminate\Queue\SerializesModels;

class ActiveplanMail extends Mailable
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

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            // Forzamos el uso del subdominio verificado
            from: new Address('contacto@mail.fubolzona.com', 'Fubol'), 
           
            subject: '¡Bienvenido a Fubol! Todo listo para el pitazo inicial ⚽',
        );
    }


     public function build()
    {
        return $this
                    ->view('emails.Activeplan')
                    ->with([
                        'usuario' => $this->usuario,
                    ]);
    }
    /**
     * Get the message content definition.
     */
   

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
