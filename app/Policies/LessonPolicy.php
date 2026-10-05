<?php

namespace App\Policies;

use App\Models\User;

class LessonPolicy
{
    public function index(User $user): bool
    {
        return true;
    }
}
