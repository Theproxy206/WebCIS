<?php

namespace Database\Seeders;

use App\Enums\UserType;
use App\Models\User;
use Illuminate\Database\Seeder;

class UserMedalSeeder extends Seeder
{
    public function run(): void
    {
        $student = User::where('user_type', UserType::Student)
            ->firstOrFail();

        $medals = [
            [
                'id' => 1,
                'obtained_at' => now()->subDays(5),
            ],
        ];

        foreach ($medals as $medal) {
            $student->medals()->attach(
                $medal['id'],
                [
                    'obtained_at' => $medal['obtained_at'],
                ]
            );
        }
    }
}