<?php

namespace App\Http\Services;

use App\Models\Course;
use App\Models\User;
use App\Models\Lesson;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Builder;
use App\Enums\EnrollmentStatus;
use App\Enums\OrderDirection;

class EnrollmentService {
    private function activeCourses(User $user, array $statuses = [EnrollmentStatus::InProgress, EnrollmentStatus::Completed]): BelongsToMany
    {
        return $user->courses()
        ->wherePivotIn('status', $statuses);
    }

    private function completedLessons(User $user, Collection $courseTokens): BelongsToMany
    {
        return $user->lessons()
        ->wherePivot('completed', true)
        ->whereIn('fk_lessons_courses', $courseTokens);
    }

    private function lessonsInCourses(Collection $courseTokens): Builder
    {
        return Lesson::whereIn(
            'fk_lessons_courses',
            $courseTokens
        );
    }

    public function paginate(User $user, array $statuses, int $perPage = 10, OrderDirection $order = OrderDirection::Asc, string $orderBy = 'cou_title'): LengthAwarePaginator
    {
        return $user->courses()
        ->wherePivotIn('status', $statuses)
        ->orderBy($orderBy, $order->value)
        ->paginate($perPage);
    }

    public function count(User $user, ?array $statuses = null): int
    {
        if ($statuses === null) {
            $statuses = [EnrollmentStatus::InProgress];
        }

        return $user->courses()
        ->wherePivotIn('status', $statuses)
        ->count();
    }

    public function findCourse(User $user, string $token): Course
    {
        return $user->courses()->findOrFail($token);
    }

    public function has(User $user, string $token): bool
    {
        return $user->courses()
        ->whereKey($token)
        ->exists();
    }

    public function progress(User $user): float
    {
        $courseTokens = $this
        ->activeCourses($user)
        ->pluck('cou_token');

        $completedLessons = $this
        ->completedLessons($user, $courseTokens)
        ->count();
        
        $totalLessons = $this
        ->lessonsInCourses($courseTokens)
        ->count();

        return $totalLessons > 0 ? round(($completedLessons / $totalLessons) * 100, 2) : 0;
    }

    public function courseProgress(User $user, ?string $token = null): float
    {
        if ($token === null) {
            $token = $this
            ->activeCourses($user, [EnrollmentStatus::InProgress])
            ->orderByPivot('last_accessed_at', 'desc')
            ->firstOrFail()
            ->cou_token;
        }

        $completedLessons = $user->lessons()
        ->wherePivot('completed', true)
        ->where('fk_lessons_courses', $token)
        ->count();

        $totalLessons = Lesson::where(
            'fk_lessons_courses',
            $token
        )->count();

        return $totalLessons > 0 ? round(($completedLessons / $totalLessons) * 100, 2) : 0;
    }

    public function recentCourses(User $user, int $limit = 5, OrderDirection $order = OrderDirection::Desc, ?array $statuses = null): Collection
    {
        if ($statuses === null) {
            $statuses = [EnrollmentStatus::InProgress, EnrollmentStatus::Completed];
        }

        return $user
        ->courses()
        ->wherePivotIn('status', $statuses)
        ->wherePivotNotNull('last_accessed_at')
        ->orderByPivot('last_accessed_at', $order->value)
        ->limit($limit)
        ->get();
    }
}