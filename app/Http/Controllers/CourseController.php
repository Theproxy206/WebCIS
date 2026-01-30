<?php

namespace App\Http\Controllers;

use App\Http\Services\ExplorerService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

class CourseController extends Controller
{
    /**
     * @return JsonResponse
     */
    public function getExplorerCourses(): JsonResponse
    {
        try {
            $courses = \App\Http\Services\ExplorerCourseService::execute();

            return response()->json([
                'status' => 'success',
                'data' => $courses
            ], 200);

        } catch (\Exception $e) {
            Log::error("Error en getExplorerCourses: " . $e->getMessage());

            return response()->json([
                'status' => 'error',
                'message' => 'Internal Server Error',
                'code' => 500
            ], 500);
        }
    }
}
