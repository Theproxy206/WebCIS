<?php

namespace Database\Seeders;

use App\Models\Course;
use App\Models\Category;
use Illuminate\Database\Seeder;

class CategoryCourseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $courseLaravel = Course::where('cou_code', 'LARAVEL-101')->firstOrFail();
        $courseGit = Course::where('cou_code', 'GIT-101')->firstOrFail();
        $categoriesLaravel = Category::whereIn('cat_name', ['Frameworks', 'Web', 'Servidores'])->get('cat_serial');
        $categoriesGit = Category::whereIn('cat_name', ['Herramientas'])->get('cat_serial');

        $courseLaravel->categories()->attach(
            $categoriesLaravel
        );

        $courseGit->categories()->attach(
            $categoriesGit
        );
    }
}
