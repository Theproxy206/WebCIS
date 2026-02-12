<?php

namespace App\Services;

use App\Enums\TokenPurpose;
use App\Enums\TokenType;
use App\Models\User;
use App\Services\TokenGeneratorService;
use App\Services\TokenSavingService;
use App\Services\EmailSenderService;
use App\Mail\ResetPasswordEmail; // Deberás crear este Mailable
use Illuminate\Support\Facades\Hash;

class PasswordResetService
{
    /**
     * Phase 1: Generate token and send email using your custom services.
     */
    public function sendLink(array $data): void
    {
        $email = $data['email'];
        $user = User::where('user_email', $email)->first();

        // If user doesn't exist, we do nothing (Privacy),
        // but we don't throw an error so the controller returns 202.
        if ($user) {
            $tokenPayload = TokenGeneratorService::generateToken(TokenType::Token);

            TokenSavingService::store(
                $email,
                $tokenPayload['hash'],
                TokenPurpose::PasswordReset
            );

            EmailSenderService::send(
                $email,
                new ResetPasswordEmail($tokenPayload['plain'])
            );
        }
    }

    /**
     * Phase 3: Update the password in the database.
     * Note: Token was already verified and deleted in Phase 2 by TokenCheckService.
     */
    public function reset(array $data): void
    {
        $user = User::where('user_email', $data['email'])->firstOrFail();

        $user->forceFill([
            'user_password' => Hash::make($data['password'])
        ])->save();
    }
}
