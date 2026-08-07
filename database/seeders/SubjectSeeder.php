<?php

namespace Database\Seeders;

use App\Models\Subject;
use Illuminate\Database\Seeder;

class SubjectSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $subjects = [
            [
                'code' => '8J4',
                'name' => 'Programación Web',
            ],
            [
                'code' => '6J3',
                'name' => 'Administración de Bases de Datos',
            ],
            [
                'code' => '6J1',
                'name' => 'Lenguajes y Automatas I',
            ]
        ];

        foreach ($subjects as $data) {
            Subject::create([
                'sub_code' => $data['code'],
                'sub_name' => $data['name'],
            ]);
        }
    }
}
