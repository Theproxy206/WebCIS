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
            'Frameworks',
            'Web',
            'Herramientas',
            'Compiladores',
            'Servidores',
            'Redes'
        ];

        foreach ($categories as $category) {
            Category::create([
                'cat_name' => $category,
            ]);
        }
    }
}
