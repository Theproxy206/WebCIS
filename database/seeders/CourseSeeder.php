<?php

namespace Database\Seeders;

use App\Enums\CourseStatus;
use App\Models\Course;
use Illuminate\Database\Seeder;

class CourseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $courses = [
            [
                'title' => 'Laravel desde Cero: El framework moderno de PHP',
                'short_title' => 'Laravel desde cero',
                'description' => 'Curso de Laravel',
                'code' => 'LARAVEL-101',
                'status' => CourseStatus::Published,
                'path_icon' => null,
            ],
            [
                'title' => 'Docker desde Cero: Aprendiendo sobre contenerización',
                'short_title' => 'Docker desde cero',
                'description' => 'Curso de Docker',
                'code' => 'GIT-101',
                'status' => CourseStatus::Published,
                'path_icon' => null,
            ],
        ];

        foreach ($courses as $data) {
            Course::create([
                'cou_title' => $data['title'],
                'cou_short_title' => $data['short_title'],
                'cou_description' => $data['description'],
                'cou_code' => $data['code'],
                'cou_status' => $data['status'],
                'cou_path_icon' => $data['path_icon'],
            ]);
        }
    }
}