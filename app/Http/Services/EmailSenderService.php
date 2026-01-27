<?php

namespace App\Http\Services;

use App\Exceptions\Auth\EmailSenderException;
use Illuminate\Mail\Mailable;
use Illuminate\Support\Facades\Mail;
use RuntimeException;

class EmailSenderService {
    /**
     * Sends an email
     *
     * @param string $email destination email
     * @param Mailable $mail mail to be sent
     * @throws EmailSenderException if it fails to send the email
     */
    public function send(string $email, Mailable $mail) : void
    {
        try {
            Mail::to($email)->queue($mail);
        } catch (\Throwable $e) {
            report($e);

            throw new EmailSenderException($e);
        }
    }
}
