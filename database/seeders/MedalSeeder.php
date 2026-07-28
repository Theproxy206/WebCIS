<?php

namespace Database\Seeders;

use App\Models\Medal;
use Illuminate\Database\Seeder;

class MedalSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $medals = [
            [
                'name' => 'Testing',
                'description' => 'Felicidades! Eres un usuario de prueba de WebCIS.',
                'path_image' => null,
            ],
            [
                'name' => 'Primer Curso',
                'description' => 'Te inscribiste a tu primer curso!',
                'path_image' => null,
            ],
        ];

        foreach ($medals as $medal) {
            Medal::create([
                'med_name' => $medal['name'],
                'med_description' => $medal['description'],
                'med_path_image' => $medal['path_image'],
            ]);
        }
    }
}