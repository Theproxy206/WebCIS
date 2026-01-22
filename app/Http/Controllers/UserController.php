<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Services\LoginService;

class UserController extends Controller
{
    public function __construct(
        private LoginService $loginService
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
}
