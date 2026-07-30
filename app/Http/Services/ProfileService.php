<?php

namespace App\Http\Services;

use App\Models\Medal;
use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use App\Enums\OrderDirection;
use Illuminate\Support\Collection;

class ProfileService {
    public function update(User $user, array $data): User
    {
        $user->fill([
            'user_username' => $data['username'] ?? $user->user_username,
            'user_description' => $data['description'] ?? $user->user_description,
            'user_name' => $data['name'] ?? $user->user_name,
            'user_surname' => $data['surname'] ?? $user->user_surname,
            'user_second_surname' => $data['second_surname'] ?? $user->user_second_surname,
        ]);

        $user->save();

        return $user;
    }
}