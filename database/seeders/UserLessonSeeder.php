<?php

namespace Database\Seeders;

use App\Enums\UserType;
use App\Models\Lesson;
use App\Models\User;
use Illuminate\Database\Seeder;

class UserLessonSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $student = User::where('user_type', UserType::Student)
            ->firstOrFail();

        $extern = User::where('user_type', UserType::Extern)
            ->firstOrFail();

        /*
        |--------------------------------------------------------------------------
        | Curso 000000000001
        | 3 de 5 lecciones completadas
        |--------------------------------------------------------------------------
        */

        $courseOneLessons = Lesson::where(
            'fk_lessons_courses',
            '000000000001'
        )
        ->orderBy('les_serial')
        ->get();

        foreach ($courseOneLessons as $index => $lesson) {

            $student->lessons()->attach(
                $lesson,
                [
                    'completed' => $index < 3,
                ]
            );

        }

        /*
        |--------------------------------------------------------------------------
        | Curso 000000000002
        | Curso completado
        |--------------------------------------------------------------------------
        */

        $courseTwoLessons = Lesson::where(
            'fk_lessons_courses',
            '000000000002'
        )
        ->orderBy('les_serial')
        ->get();

        foreach ($courseTwoLessons as $lesson) {

            $student->lessons()->attach(
                $lesson,
                [
                    'completed' => true,
                ]
            );

        }

        foreach ($courseTwoLessons as $index => $lesson) {

            $extern->lessons()->attach(
                $lesson,
                [
                    'completed' => $index < 2,
                ]
            );

        }
    }
}