<?php

namespace App\Http\Controllers;

use App\Enums\TokenPurpose;
use App\Enums\TokenType;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\SendEmailVerificationRequest;
use App\Http\Requests\VerifyEmailRequest;
use App\Http\Services\EmailSenderService;
use App\Http\Services\LoginService;
use App\Http\Services\TokenSavingService;
use App\Http\Services\TokenGeneratorService;
use App\Mail\VerificationEmail;
use Illuminate\Http\JsonResponse;

class UserController extends Controller
{
    public function login(LoginRequest $request) {
        $login = $request->login;
        $password = $request->password;

        $field = filter_var($login, FILTER_VALIDATE_EMAIL) ? 'user_email' : 'user_username';

        $credentials = [
            $field => $login,
            'password' => $password,
        ];

        return LoginService::execute($credentials, $request);
    }

    /**
     * @param SendEmailVerificationRequest $request
     * @return JsonResponse response to the client
     */
    public function sendVerificationEmail(SendEmailVerificationRequest $request) : JsonResponse
    {
        $tokenPayload = TokenGeneratorService::generateToken(TokenType::Token);

        TokenSavingService::store($request->email, $tokenPayload['hash'], TokenPurpose::EmailVerification);

        EmailSenderService::send($request->email, new VerificationEmail($tokenPayload['plain']));

        return response()->json([
            'message' => 'If the email exists, a verification email will be sent'
        ], 202);
    }

    public function verifyEmail(VerifyEmailRequest $request)
    {
        //
    }

}
