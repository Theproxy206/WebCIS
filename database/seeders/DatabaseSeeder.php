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
            UserSeeder::class,
            MedalSeeder::class,
            CourseSeeder::class,
            LessonSeeder::class,

            EnrollmentSeeder::class,
            UserLessonSeeder::class,
            UserMedalSeeder::class,
        ]);
    }
}