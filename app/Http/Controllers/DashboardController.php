<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Services\MedalService;
use App\Http\Services\EnrollmentService;
use App\Http\Resources\UserResource;

class DashboardController extends Controller
{
    public function __construct(
        private readonly MedalService $medalService,
        private readonly EnrollmentService $enrollments
    ) {}

    public function index(Request $request) {
        return response()->json([
            'user' => new UserResource($request->user()),
            'medals' => $this->medalService->count($request->user()),
            'progress' => $this->enrollments->progress($request->user()),
            'recent_courses' => $this->enrollments->recentCourses($request->user())->map(function ($course) {
                return [
                    'token' => $course->cou_token,
                    'title' => $course->cou_title,
                    'short_title' => $course->cou_short_title,
                    'icon' => $course->cou_path_icon,
                    'last_accessed_at' => $course->enrollment->last_accessed_at,
                ];
            }),
        ]);
    }
}