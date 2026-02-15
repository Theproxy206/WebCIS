<?php

namespace App\Http\Services;

use App\Enums\TokenPurpose;
use App\Enums\TokenType;
use App\Models\User;
use App\Http\Services\TokenGeneratorService;
use App\Http\Services\TokenSavingService;
use App\Http\Services\EmailSenderService;
use App\Mail\ResetPasswordEmail;
use Illuminate\Support\Facades\Hash;

class PasswordResetService
{
    public function __construct(
        private readonly EmailSenderService $emailSend
    ){}
    /**
     * Phase 1: Generate token and send email using your custom services.
     */
    public function sendLink(string $email, string $code): void
    {
        $user = User::query()->where('user_email', $email)->first();

        // If user doesn't exist, we do nothing (Privacy),
        // but we don't throw an error so the controller returns 202.
        if ($user) {
            $this->emailSend->send($email, new ResetPasswordEmail($code));
        }
    }

    /**
     * Phase 3: Update the password in the database.
     * Note: Token was already verified and deleted in Phase 2 by TokenCheckService.
     */
    public function reset(string $email, string $password): void
    {
        $user = User::query()->where('user_email', $email)->firstOrFail();
        $user->update([
            'user_pass' => Hash::make($password)
        ]);
    }
}
