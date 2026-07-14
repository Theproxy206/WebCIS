<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class DashboardController extends Controller
{
    public function index(Request $request) {
        //TODO:
        // Regresar datos basicos del usuario: Nombre, imagen de perfil y rol
        // Devolver ultimos 5 cursos y ultimo curso accedido
        // Devolver numero de medallas obtenidas
        // Devolver porcentaje de avance global medido por lecciones completadas / lecciones totales

        $user = $request->user();
        $username = $user->user_name;
        $userRole = $user->user_type;

        return response()->json([
            'username' => $username,
            'user_role' => $userRole
        ], 200);
    }
}