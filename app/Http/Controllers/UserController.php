<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

// Enums
use App\Enums\TokenPurpose;
use App\Enums\TokenType;
use App\Enums\UserType;

// Requests
use App\Http\Requests\LoginRequest;
use App\Http\Requests\LogoutRequest;
use App\Http\Requests\RegisterRequest;
use App\Http\Requests\SendEmailVerificationRequest;
use App\Http\Requests\VerifyEmailRequest;
use App\Http\Requests\ResetPasswordRequest;

// Services
use App\Http\Services\EmailSenderService;
use App\Http\Services\LoginService;
use App\Http\Services\LogoutService;
use App\Http\Services\PasswordResetService;
use App\Http\Services\TokenCheckService;
use App\Http\Services\TokenSavingService;
use App\Http\Services\TokenGeneratorService;
use App\Http\Services\UserRegisterService;
use App\Mail\VerificationEmail;
use App\Models\User;
use App\Models\UserTemp;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;

// Exceptions
use App\Exceptions\Auth\EmailSenderException;
use App\Exceptions\Auth\TokenValidationException;
use App\Exceptions\Auth\UserStorageException;

class UserController extends Controller
{
    public function __construct(
        private readonly TokenGeneratorService $tokenGenerator,
        private readonly TokenSavingService    $tokenSaving,
        private readonly TokenCheckService     $tokenCheck,
        private readonly PasswordResetService $passwordService,
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
    
    /**
     * Recovery - Phase 1: Request Reset Link
     */
    public function sendResetLink(Request $request): JsonResponse
    {
        $request->validate(['email' => 'required|email']);

        try {
            // El servicio maneja la generación y el envío
            $this->passwordService->sendLink($request->only('email'));
        } catch (EmailSenderException $e) {
            // Error técnico si falla el servidor de correos
            return response()->json([
                'error' => 'No pudimos enviar el correo de recuperación. Inténtalo más tarde.'
            ], 503);
        } catch (\Exception $e) {
            // Error genérico por seguridad
            return response()->json([
                'error' => 'Ocurrió un error inesperado al procesar la solicitud.'
            ], 500);
        }

        return response()->json([
            'message' => 'If the email exists, a verification email will be sent'
        ], 202);
    }

    /**
     * Recovery - Phase 2: Affirmative Validation
     */
    public function validateResetToken(Request $request): JsonResponse
    {
        $request->validate([
            'email' => 'required|email',
            'token' => 'required|string'
        ]);

        try {
            $this->tokenCheckService->verify(
                $request->input('email'),
                $request->input('token'),
                TokenPurpose::PasswordReset
            );
        } catch (TokenValidationException $e) {
            // Error si el token es incorrecto o expiró
            return response()->json([
                'error' => 'El código de seguridad es inválido o ha expirado.'
            ], 422);
        }

        return response()->json([
            'message' => 'Token validated successfully. You may now set a new password.'
        ], 200);
    }

    /**
     * Recovery - Phase 3: Execute Reset
     */
    public function resetPassword(ResetPasswordRequest $request): JsonResponse
    {
        try {
            $this->passwordService->reset($request->validated());
        } catch (UserStorageException $e) {
            // Error si falla el guardado final en la base de datos
            return response()->json([
                'error' => 'No se pudo actualizar la contraseña. Contacta a soporte.'
            ], 500);
        }

        return response()->json([
            'message' => 'Password updated successfully'
        ], 200);
    }
}
