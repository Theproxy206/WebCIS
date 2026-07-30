<?php

namespace App\Http\Services;

use App\Models\User;
use App\Http\Services\StorageService;
use Illuminate\Http\UploadedFile;

class ProfileService {
    public function __construct(
        private readonly StorageService $storage
    ) {}

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

    public function updateProfilePicture(User $user, UploadedFile $file): User
    {
        $user->path_profile_picture = $this->storage->store($file, 'profiles');

        return $user;
    }
}