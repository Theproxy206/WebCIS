<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\LoginRequest;
use App\Http\Services\LoginService;
use App\Http\Services\PasswordResetService;

class UserController extends Controller
{
    public function __construct(
        private LoginService $loginService,
        private PasswordResetService $passwordService
    ) {}

    /**
     * Autenticación de usuario
     */
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
     * Enviar enlace de recuperación de contraseña
     */
    public function sendResetLink(Request $request) {
        // Validamos aquí o puedes crear un Request específico
        $request->validate(['email' => 'required|email']);
        
        return $this->passwordService->sendLink($request->only('email'));
    }

    /**
     * Ejecutar el cambio de contraseña
     */
    public function resetPassword(Request $request) {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|min:8|confirmed',
        ]);

        return $this->passwordService->reset($request->all());
    }
}