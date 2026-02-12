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
use App\Http\Requests\RegisterRequest;
use App\Http\Requests\SendEmailVerificationRequest;
use App\Http\Requests\VerifyEmailRequest;
use App\Http\Requests\ResetPasswordRequest;

// Services
use App\Services\LoginService;
use App\Services\PasswordResetService;
use App\Services\TokenCheckService;
use App\Services\UserRegisterService;
use App\Services\EmailSenderService;

// Exceptions
use App\Exceptions\Auth\EmailSenderException;
use App\Exceptions\Auth\TokenValidationException;
use App\Exceptions\Auth\UserStorageException;

class UserController extends Controller
{
    public function __construct(
        private LoginService $loginService,
        private PasswordResetService $passwordService,
        private TokenCheckService $tokenCheckService
    ) {}

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
