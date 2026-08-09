<?php

namespace App\Http\Controllers;

use App\Http\Requests\CourseIndexRequest;
use App\Http\Resources\CourseResource;
use App\Http\Resources\CourseSummaryResource;
use App\Http\Services\CourseService;
use Illuminate\Http\Request;

class CourseController extends Controller
{
    public function __construct(
        private readonly CourseService $courseService
    ) {}

    /**
     * Display a listing of the resource.
     */
    public function index(CourseIndexRequest $request)
    {
        return CourseSummaryResource::collection(
            $this->courseService->paginate(
                $request->validated()
            )
        );
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
    public function show(string $code)
    {
        Return new CourseResource($this->courseService->getCourse($code));
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
