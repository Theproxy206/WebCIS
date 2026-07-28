<?php

namespace Database\Seeders;

use App\Enums\EnrollmentStatus;
use App\Enums\UserType;
use App\Enums\CourseRole;
use App\Models\User;
use Illuminate\Database\Seeder;

class EnrollmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $student = User::where('user_type', UserType::Student)->firstOrFail();
        $professor = User::where('user_type', UserType::Professor)->firstOrFail();
        $extern = User::where('user_type', UserType::Extern)->firstOrFail();

        /*
        |--------------------------------------------------------------------------
        | Alumno
        |--------------------------------------------------------------------------
        */

        $student->courses()->attach(
            '000000000001',
            [
                'status' => EnrollmentStatus::InProgress,
                'role' => null,
                'created_at' => null,
                'joined_at' => now()->subDays(12),
                'last_accessed_at' => now()->subHours(2),
                'completed_at' => null,
            ]
        );

        $student->courses()->attach(
            '000000000002',
            [
                'status' => EnrollmentStatus::Completed,
                'role' => null,
                'created_at' => null,
                'joined_at' => now()->subDays(25),
                'last_accessed_at' => now()->subDays(2),
                'completed_at' => now()->subDays(3),
            ]
        );

        /*
        |--------------------------------------------------------------------------
        | Profesor
        |--------------------------------------------------------------------------
        */

        $professor->courses()->attach(
            '000000000001',
            [
                'status' => null,
                'role' => CourseRole::Owner,
                'created_at' => now()->subMonths(2),
                'joined_at' => null,
                'last_accessed_at' => now()->subDays(4),
                'completed_at' => null,
            ]
        );

        $professor->courses()->attach(
            '000000000002',
            [
                'status' => null,
                'role' => CourseRole::Owner,
                'created_at' => now()->subMonths(1),
                'joined_at' => null,
                'last_accessed_at' => now()->subDays(6),
                'completed_at' => null,
            ]
        );

        /**
         * Externo
         */

        $extern->courses()->attach(
            '000000000002',
            [
                'status' => EnrollmentStatus::InProgress,
                'role' => CourseRole::Collaborator,
                'created_at' => now()->subMonths(1),
                'joined_at' => now()->subDays(14),
                'last_accessed_at' => now()->subDays(6),
                'completed_at' => null,
            ]
        );
    }
}