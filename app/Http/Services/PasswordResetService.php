<?php

namespace App\Http\Services;

use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\JsonResponse;

class PasswordResetService
{
    /**
     * Lógica para enviar el link al email
     */
    public function sendLink(array $email): JsonResponse
    {
        $status = Password::sendResetLink($email);

        return $status === Password::RESET_LINK_SENT
            ? response()->json(['message' => 'Te hemos enviado un correo con el enlace de recuperación.'])
            : response()->json(['error' => 'No pudimos enviar el correo.'], 400);
    }

    /**
     * Lógica para resetear la contraseña en la DB
     */
    public function reset(array $data): JsonResponse
    {
        $status = Password::reset($data, function ($user, $password) {
            $user->forceFill([
                'password' => Hash::make($password)
            ])->save();
        });

        return $status === Password::PASSWORD_RESET
            ? response()->json(['message' => 'Tu contraseña ha sido actualizada con éxito.'])
            : response()->json(['error' => 'El token o el email son inválidos.'], 400);
    }
}