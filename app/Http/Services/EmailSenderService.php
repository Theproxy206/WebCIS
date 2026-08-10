<?php

namespace App\Http\Services;

use App\Exceptions\Auth\EmailSenderException;
use Illuminate\Mail\Mailable;
use Illuminate\Support\Facades\Mail;
use RuntimeException;

class EmailSenderService
{
    /**
     * Sends an email to one or multiple recipients.
     *
     * @param string|array<string> $emails
     * @param Mailable $mail
     * @throws EmailSenderException
     */
    public function send(string|array $emails, Mailable $mail): void
    {
        try {
            Mail::to($emails)->queue($mail);
        } catch (\Throwable $e) {
            report($e);

            throw new EmailSenderException($e);
        }
    }
}
