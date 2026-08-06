<?php

namespace Database\Seeders;

use App\Models\Course;
use App\Models\Lesson;
use Illuminate\Database\Seeder;

class LessonSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $courses = [
            'LARAVEL-101' => [
                'Introducción',
                'Instalación del entorno',
                'Primer proyecto',
                'Controladores',
                'Rutas',
            ],

            'GIT-101' => [
                '¿Qué es Git?',
                'Repositorios',
                'Commits',
                'Branches',
                'GitHub',
            ],
        ];

        foreach ($courses as $courseCode => $titles) {

            $course = Course::where('cou_code', $courseCode)->firstOrFail();

            foreach ($titles as $order => $title) {

                Lesson::create([
                    'les_title' => $title,
                    'les_short_title' => mb_strimwidth($title, 0, 60),
                    'les_order' => $order + 1,
                    'fk_lessons_courses' => $course->cou_id,
                ]);
            }
        }
    }
}