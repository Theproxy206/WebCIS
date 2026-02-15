<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ResetPasswordEmail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * El token es lo que recibimos del PasswordResetService
     */
    public function __construct(
        public string $token
    ) {}

    /**
     * Definimos el asunto que verá el usuario en su bandeja
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: '[WebCIS] Recuperación de contraseña',
        );
    }

    /**
     * Aquí ocurre la magia: enviamos los textos al molde 'auth-token'
     */
    public function content(): Content
    {
        return new Content(
            view: 'mails.auth-token',
            with: [
                'subject'   => 'Recuperar contraseña',
                'introText' => 'Recibimos una solicitud para <strong>restablecer la contraseña</strong> de tu cuenta. Si fuiste tú, utiliza este código:',
                'footerText' => 'Este código es de un solo uso y expira en 10 minutos por seguridad. Si no solicitaste este cambio, protege tu cuenta.'
            ],
        );
    }

    /**
     * Sin archivos adjuntos por ahora
     */
    public function attachments(): array
    {
        return [];
    }
}
