<?php

namespace App\Http\Services;

use App\Enums\UserType;
use App\Exceptions\Auth\UserStorageException;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserRegisterService
{
    public function create(string $email, string $username, string $password, string $name, string $surname, string $secondSurname, UserType $type) : User
    {
        $password = Hash::make($password);

        try {
            $newUser = new User;
            $newUser->user_email = $email;
            $newUser->user_username = $username;
            $newUser->user_pass = $password;
            $newUser->user_name = $name;
            $newUser->user_surname = $surname;
            $newUser->user_second_surname = $secondSurname;
            $newUser->user_type = $type;
            $newUser->save();

            return $newUser;
        } catch (\Throwable $e) {
            report($e);

            throw new UserStorageException("Failed to create new user", $e);
        }
    }
}
