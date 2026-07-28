<?php

namespace Database\Seeders;

use App\Enums\UserType;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $password = Hash::make('password');

        $users = [
            [
                'email' => 'student@webcis.test',
                'username' => 'student',
                'control_number' => '22990001',
                'description' => 'Usuario de prueba (Alumno).',
                'path_profile_picture' => null,
                'path_banner' => null,
                'type' => UserType::Student,
                'name' => 'Alumno',
                'surname' => 'Prueba',
                'second_surname' => 'WebCIS',
            ],
            [
                'email' => 'professor@webcis.test',
                'username' => 'professor',
                'control_number' => null,
                'description' => 'Usuario de prueba (Profesor).',
                'path_profile_picture' => null,
                'path_banner' => null,
                'type' => UserType::Professor,
                'name' => 'Profesor',
                'surname' => 'Prueba',
                'second_surname' => 'WebCIS',
            ],
            [
                'email' => 'extern@webcis.test',
                'username' => 'extern',
                'control_number' => null,
                'description' => 'Usuario de prueba (Externo).',
                'path_profile_picture' => null,
                'path_banner' => null,
                'type' => UserType::Extern,
                'name' => 'Externo',
                'surname' => 'Prueba',
                'second_surname' => 'WebCIS',
            ],
            [
                'email' => 'admin@webcis.test',
                'username' => 'admin',
                'control_number' => null,
                'description' => 'Administrador del sistema.',
                'path_profile_picture' => null,
                'path_banner' => null,
                'type' => UserType::Admin,
                'name' => 'Administrador',
                'surname' => 'WebCIS',
                'second_surname' => null,
            ]
        ];

        foreach ($users as $data) {
            User::create([
                'user_id' => Str::uuid(),
                'user_email' => $data['email'],
                'user_username' => $data['username'],
                'user_control_number' => $data['control_number'],
                'user_description' => $data['description'],
                'user_path_profile_picture' => $data['path_profile_picture'],
                'user_path_banner' => $data['path_banner'],
                'user_pass' => $password,
                'user_type' => $data['type'],
                'user_name' => $data['name'],
                'user_surname' => $data['surname'],
                'user_second_surname' => $data['second_surname'],
            ]);
        }
    }
}