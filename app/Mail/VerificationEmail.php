<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class VerificationEmail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * @param string $token El código que viene del servicio
     */
    public function __construct(
        public string $token
    ) {}

    /**
     * El asunto que Sergio definió originalmente
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Verificación de correo',
        );
    }

    /**
     * Aquí inyectamos los textos CLAVE de Sergio
     */
    public function content(): Content
    {
        return new Content(
            view: 'mails.auth-token',
            with: [
                'subject'   => 'Verificación de correo',
                // TEXTO EXACTO DE SERGIO (Copiado y pegado tal cual):
                'introText' => 'Recibimos una solicitud para verificar esta dirección de correo electrónico. Si fuiste tú, usa el siguiente código para completar el proceso:',

                // TEXTO EXACTO DE SERGIO (Copiado y pegado tal cual):
                'footerText' => 'Este código tiene una vigencia limitada. Si no solicitaste esta verificación, puedes ignorar este correo con tranquilidad.'
            ],
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
