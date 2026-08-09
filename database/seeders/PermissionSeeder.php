<?php

namespace Database\Seeders;

use App\Models\Permission;
use Illuminate\Database\Seeder;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissions = [
            [
                'code' => 'create-course',
                'name' => 'Crear Curso',
                'description' => 'El usuario puede crear un curso en la plataforma',
            ],
            [
                'code' => 'upload-material',
                'name' => 'Subir Material',
                'description' => 'El usuario puede subir archivos en la sección de materiales',
            ]
        ];

        foreach ($permissions as $data){
            Permission::create([
                'per_code' => $data['code'],
                'per_name' => $data['name'],
                'per_description' => $data['description'],
            ]);
        }
    }
}
