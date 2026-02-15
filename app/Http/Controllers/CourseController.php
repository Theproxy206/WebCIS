<?php

namespace App\Http\Controllers;

use App\Http\Services\ExplorerCourseService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class CourseController extends Controller
{
    public function getExplorerCourses(Request $request): JsonResponse
    {
        try {
            $courses = ExplorerCourseService::Execute($request);

            return response()->json([
                'status' => 'success',
                'data' => $courses
            ], 200);
        } catch (\Exception $e) {
            Log::error("Error en ExplorerCourses: " . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Error al consultar los cursos'
            ], 500);
        }
    }
}
