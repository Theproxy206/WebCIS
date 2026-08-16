<?php

namespace App\Http\Controllers;

use App\Enums\CourseRole;
use App\Http\Requests\CourseCreateRequest;
use App\Http\Requests\CourseIconUpdateRequest;
use App\Http\Requests\CourseIndexRequest;
use App\Http\Requests\CourseReviewActionRequest;
use App\Http\Requests\CourseUpdateRequest;
use App\Http\Requests\ImageUploadRequest;
use App\Http\Resources\CourseResource;
use App\Http\Resources\CourseSummaryResource;
use App\Http\Services\CourseService;
use App\Http\Services\EmailSenderService;
use App\Http\Services\StorageService;
use App\Mail\CourseApprovedEmail;
use App\Mail\CoursePublishedEmail;
use App\Mail\CourseRejectedEmail;
use App\Models\Course;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\JsonResponse;

class CourseController extends Controller
{
    use AuthorizesRequests;

    public function __construct(
        private readonly CourseService $courseService,
        private readonly StorageService $storage,
        private readonly EmailSenderService $email
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
            'url' => $this->storage->url($path),
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
    public function update(CourseUpdateRequest $request, string $code)
    {
        $course = Course::where('cou_code', $code)->firstOrFail();
        $this->authorize('update', $course);

        $course = $this->courseService->updateCourse($request->validated(), $code);

        return response()->json([
            'message' => 'Course updated succesfully',
            'course' => new CourseSummaryResource($course),
        ]);
    }

    /**
     * Remove the specified resource from storage.
     * 
     * @param string $code
     * @return JsonResponse
     */
    public function destroy(string $code): JsonResponse
    {
        $course = Course::where('cou_code', $code)->firstOrFail();
        $this->authorize('destroy', $course);

        $this->courseService->delete($code);

        return response()->json(status:204);
    }

    public function sendForApproval(string $code)
    {
        $course = Course::where('cou_code', $code)->firstOrFail();
        $this->authorize('sendForApproval', $course);

        return response()->json([
            'message' => 'Send for approval',
            'course' => new CourseSummaryResource($this->courseService->seekApproval($code)),
        ]);
    }

    public function approve(CourseReviewActionRequest $request, string $code)
    {
        $course = Course::where('cou_code', $code)->firstOrFail();
        $this->authorize('approve', $course);

        $course = $this->courseService->approve($code);

        $recipients = $course->users()
            ->wherePivotIn('role', [
                CourseRole::Owner->value,
                CourseRole::Collaborator->value,
                CourseRole::Advisor->value,
            ])
            ->pluck('user_email')
            ->all();

        $this->email->send($recipients, new CourseApprovedEmail($course, $request->validated('commentary')));

        return response()->json([
            'message' => 'Course approved succesfully',
            'Course' => new CourseSummaryResource($course),
        ]);
    }

    public function reject(CourseReviewActionRequest $request, string $code)
    {
        $course = Course::where('cou_code', $code)->firstOrFail();
        $this->authorize('reject', $course);

        $course = $this->courseService->reject($code);

        $recipients = $course->users()
            ->wherePivotIn('role', [
                CourseRole::Owner->value,
                CourseRole::Collaborator->value,
                CourseRole::Advisor->value,
            ])
            ->pluck('user_email')
            ->all();

        $this->email->send($recipients, new CourseRejectedEmail($course, $request->validated('commentary')));

        return response()->json([
            'message' => 'Course rejected',
            'Course' => new CourseSummaryResource($course),
        ]);
    }

    public function publish(string $code)
    {
        $course = Course::where('cou_code', $code)->firstOrFail();
        $this->authorize('publish', $course);

        $course = $this->courseService->publish($code);

        $recipients = $course->users()
            ->wherePivotIn('role', [
                CourseRole::Owner->value,
                CourseRole::Collaborator->value,
                CourseRole::Advisor->value,
            ])
            ->pluck('user_email')
            ->all();

        $this->email->send($recipients, new CoursePublishedEmail($course));

        return response()->json([
            'message' => 'Course published succesfully',
            'course' => new CourseSummaryResource($course),
        ]);
    }

    public function updateIcon(CourseIconUpdateRequest $request, string $code): JsonResponse
    {
        $course = Course::where('cou_code', $code)->firstOrFail();
        $this->authorize('updateIcon', $course);

        $course = $this->courseService->updateIcon($request->validated('icon'), $code);

        return response()->json([
            'message' => 'Updated course icon',
            'course' => new CourseSummaryResource($course),
        ]);
    }
}
