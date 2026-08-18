<?php

namespace Database\Seeders;

use App\Enums\EnrollmentStatus;
use App\Enums\UserType;
use App\Enums\CourseRole;
use App\Models\User;
use App\Models\Course;
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
        $courseLaravel = Course::where('cou_code', 'LARAVEL-101')->firstOrFail();
        $courseGit = Course::where('cou_code', 'GIT-101')->firstOrFail();

        /*
        |--------------------------------------------------------------------------
        | Alumno
        |--------------------------------------------------------------------------
        */

        $student->courses()->attach(
            $courseLaravel->cou_id,
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
            $courseGit->cou_id,
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
            $courseLaravel->cou_id,
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
            $courseGit->cou_id,
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
            $courseGit->cou_id,
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