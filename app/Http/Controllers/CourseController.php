<?php

namespace App\Http\Controllers;

use App\Http\Requests\CourseCreateRequest;
use App\Http\Requests\CourseIndexRequest;
use App\Http\Requests\ImageUploadRequest;
use App\Http\Resources\CourseResource;
use App\Http\Resources\CourseSummaryResource;
use App\Http\Services\CourseService;
use App\Http\Services\StorageService;
use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class CourseController extends Controller
{
    use AuthorizesRequests;

    public function __construct(
        private readonly CourseService $courseService,
        private readonly StorageService $storage
    ) {}

    public function storeIcon(ImageUploadRequest $request)
    {
        $this->authorize('storeIcon', Course::class);

        $path = $this->storage->store(
            $request->file('image'),
            'courses/icons'
        );

        return response()->json([
            'path' => $path,
            'url' => asset('storage/' . $path),
        ], 201);
    }

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
    public function store(CourseCreateRequest $request)
    {
        $this->authorize('create', Course::class);
        
        return response()->json([
            'course' => new CourseSummaryResource($this->courseService->createCourse($request->user(), $request->validated())),
        ], 201);
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
