<?php

namespace Database\Seeders;

use App\Models\Course;
use App\Models\Subject;
use Illuminate\Database\Seeder;

class SubjectCourseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $courseLaravel = Course::where('cou_code', 'LARAVEL-101')->firstOrFail();
        $subjectsLaravel = Subject::whereIn('sub_code', ['8J4'])->get('sub_serial');

        $courseLaravel->subjects()->attach(
            $subjectsLaravel
        );
    }
}
