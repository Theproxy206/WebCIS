<?php

namespace Database\Seeders;

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
                'token' => '000000000001',
                'title' => 'Laravel desde Cero: El framework moderno de PHP',
                'short_title' => 'Laravel desde cero',
                'description' => 'Curso de Laravel',
                'code' => 'Larav-26-07-01',
                'content' => 'placeholder',
                'path_icon' => null,
            ],
            [
                'token' => '000000000002',
                'title' => 'Docker desde Cero: Aprendiendo sobre contenerización',
                'short_title' => 'Docker desde cero',
                'description' => 'Curso de Docker',
                'code' => 'Docke-26-07-01',
                'content' => 'placeholder',
                'path_icon' => null,
            ],
        ];

        foreach ($courses as $data) {
            Course::create([
                'cou_token' => $data['token'],
                'cou_title' => $data['title'],
                'cou_short_title' => $data['short_title'],
                'cou_description' => $data['description'],
                'cou_code' => $data['code'],
                'cou_content' => $data['content'],
                'cou_path_icon' => $data['path_icon'],
            ]);
        }
    }
}