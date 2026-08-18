<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            PermissionSeeder::class,
            UserSeeder::class,
            MedalSeeder::class,
            CategorySeeder::class,
            SubjectSeeder::class,
            CourseSeeder::class,
            LessonSeeder::class,

            CategoryCourseSeeder::class,
            SubjectCourseSeeder::class,
            EnrollmentSeeder::class,
            UserLessonSeeder::class,
            UserMedalSeeder::class,
        ]);
    }
}