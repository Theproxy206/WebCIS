<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Frameworks',
                'code' => 'frame',
            ],
            [
                'name' => 'Web',
                'code' => 'web',
            ],
            [
                'name' => 'Herramientas',
                'code' => 'tools',
            ],
            [
                'name' => 'Compiladores',
                'code' => 'compiler',
            ],
            [
                'name' => 'Servidores',
                'code' => 'servers',
            ],
            [
                'name' => 'Redes',
                'code' => 'redes',
            ]
        ];

        foreach ($categories as $data) {
            Category::create([
                'cat_name' => $data['name'],
                'cat_code' => $data['code'],
            ]);
        }
    }
}
