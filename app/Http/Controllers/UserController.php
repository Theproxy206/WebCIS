<?php

namespace App\Http\Controllers;

use App\Enums\TokenPurpose;
use App\Enums\TokenType;
use App\Enums\UserType;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\LogoutRequest;
use App\Http\Requests\RegisterRequest;
use App\Http\Requests\SendEmailVerificationRequest;
use App\Http\Requests\VerifyEmailRequest;
use App\Http\Services\EmailSenderService;
use App\Http\Services\LoginService;
use App\Http\Services\LogoutService;
use App\Http\Services\TokenCheckService;
use App\Http\Services\TokenSavingService;
use App\Http\Services\TokenGeneratorService;
use App\Http\Services\UserRegisterService;
use App\Mail\VerificationEmail;
use App\Models\User;
use App\Models\UserTemp;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function __construct(
        private readonly TokenGeneratorService $tokenGenerator,
        private readonly TokenSavingService    $tokenSaving,
        private readonly TokenCheckService     $tokenCheck,
        private readonly EmailSenderService    $emailSender,
        private readonly UserRegisterService   $userRegister,
        private readonly LoginService $loginService,
        private readonly LogoutService $logoutRequest
    ) {}

    public function login(LoginRequest $request) {
        $login = $request->login;
        $password = $request->password;

        $field = filter_var($login, FILTER_VALIDATE_EMAIL) ? 'user_email' : 'user_username';

        $credentials = [
            $field => $login,
            'password' => $password,
        ];

        return $this->loginService->execute($credentials, $request);
    }

    /**
     * @param SendEmailVerificationRequest $request
     * @return JsonResponse response to the client
     */
    public function sendVerificationEmail(SendEmailVerificationRequest $request) : JsonResponse
    {
        $tokenPayload = $this->tokenGenerator->generateToken(TokenType::Token);

        $this->tokenSaving->store($request->email, $tokenPayload['hash'], TokenPurpose::EmailVerification);

        $this->emailSender->send($request->email, new VerificationEmail($tokenPayload['plain']));

        return response()->json([
            'message' => 'If the email exists, a verification email will be sent'
        ], 202);
    }

    public function verifyEmail(VerifyEmailRequest $request)
    {
        $this->tokenCheck->verify($request->input('email'), $request->input('token'), TokenPurpose::EmailVerification);

        $tokenPayload = $this->tokenGenerator->generateToken(TokenType::Token, 32);
        $this->tokenSaving->store($request->input('email'), $tokenPayload['hash'], TokenPurpose::RegisterCompletion, 120);

        return response()->json([
            'message' => 'Verification successful',
            'token' => $tokenPayload['plain']
        ], 201);
    }

    public function register(RegisterRequest $request)
    {
        $this->tokenCheck->verify(
            $request->input('email'),
            $request->input('token'),
            TokenPurpose::RegisterCompletion
        );

        $newUser = $this->userRegister->create(
            $request->input('email'),
            $request->input('username'),
            $request->input('password'),
            $request->input('names'),
            $request->input('surname'),
            $request->input('second_surname'),
            UserType::from($request->input('type')),
        );

        return response()->json([
            'message' => 'User created successfully',
            'user' => $newUser,
        ], 201);
    }

    public function logout(LogoutRequest $request)
    {
        $this->logoutRequest->logout($request);

        return response()->json([
            'message' => 'User logged out successfully',
        ]);
    }
}
