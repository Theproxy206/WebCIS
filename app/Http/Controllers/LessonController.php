<?php

namespace App\Http\Controllers;

use App\Http\Resources\LessonSummaryResource;
use App\Http\Services\LessonService;
use App\Models\Course;
use App\Models\Lesson;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class LessonController extends Controller
{
    public function __construct(
        protected readonly LessonService $lessonService
    ) {}

    /**
     * Display a listing of the resource.
     * 
     * @param string $code
     * @return JsonResponse
     */
    public function index(string $code): JsonResponse
    {
        $course = Course::where('cou_code', $code)->firstOrFail();

        return response()->json([
            'lessons' => LessonSummaryResource::collection($this->lessonService->showLessons($course))
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
