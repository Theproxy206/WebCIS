<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Resources\UserResource;
use App\Http\Resources\MedalResource;
use App\Http\Resources\EnrollmentResource;
use App\Enums\OrderDirection;
use App\Http\Services\EnrollmentService;
use App\Http\Services\MedalService;
use App\Http\Service\ProfileService;
use App\Http\Requests\ProfileUpdateRequest;
use App\Http\Requests\ProfilePictureUpdateRequest;

class ProfileController extends Controller
{
    public function __construct(
        private readonly MedalService $medals,
        private readonly EnrollmentService $enrollments,
        private readonly ProfileService $profileService
    ) {}

    public function index(Request $request): JsonResponse
    {
        return response()->json([
            'user' => new UserResource($request->user()),
            'medals' => $this->medals->recent($request->user(), OrderDirection::Desc, 5)->map(function ($medal) {
                return new MedalResource($medal);
            }),
            'courses' => $this->enrollments->recentCourses($request->user(), 4, OrderDirection::Desc)->map(function ($course) {
                return new EnrollmentResource($course);
            })
        ]);
    }

    public function update(ProfileUpdateRequest $request): JsonResponse
    {
        $this->profileService->update(
            $request->user(),
            $request->validated()
        );

        return response()->json([
            'message' => 'Profile updated successfully.',
            'user' => new UserResource($user->refresh()),
        ]);
    }

    public function updateProfilePicture(ProfilePictureUpdateRequest $request): JsonResponse
    {
        this->profileService->updateProfilePicture($request->user(), $request->file('image'));

        return response()->json([
            'message' => 'Profile picture updated successfully.',
            'user' = new UserResource($user->refresh()),
        ]);
    }
}