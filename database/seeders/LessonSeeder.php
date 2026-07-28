<?php

namespace Database\Seeders;

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
            '000000000001' => [
                'Introducción',
                'Instalación del entorno',
                'Primer proyecto',
                'Controladores',
                'Rutas',
            ],

            '000000000002' => [
                '¿Qué es Git?',
                'Repositorios',
                'Commits',
                'Branches',
                'GitHub',
            ],
        ];

        foreach ($courses as $courseToken => $titles) {

            $parent = null;

            foreach ($titles as $title) {

                $lesson = Lesson::create([
                    'les_title' => $title,
                    'les_short_title' => mb_strimwidth($title, 0, 60),
                    'fk_lessons_courses' => $courseToken,
                    'fk_lessons_lessons' => $parent,
                ]);

                $parent = $lesson->les_serial;
            }
        }
    }
}