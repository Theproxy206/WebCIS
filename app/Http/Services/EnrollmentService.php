<?php

namespace App\Http\Services;

use App\Models\Course;
use App\Models\User;
use App\Models\Lesson;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Builder;
use App\Enums\EnrollmentStatus;
use App\Enums\OrderDirection;

class EnrollmentService {
    private function activeCourses(User $user): BelongsToMany
    {
        return $user->courses()
        ->wherePivotIn('status', [
            EnrollmentStatus::InProgress,
            EnrollmentStatus::Completed,
        ]);
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

    public function paginate(User $user, int $perPage = 10, OrderDirection $order = OrderDirection::Asc, ?array $statuses = null, string $orderBy = 'cou_title'): LengthAwarePaginator
    {
        $query = $user->courses()
        ->wherePivotIn('status', $status);

        if ($statuses !== null) {
            $query->wherePivotIn('status', $statuses);
        }

        return $query
        ->orderBy($orderBy, $order->value)
        ->paginate($perPage);
    }

    public function count(User $user): int
    {
        return $user->courses()->count();
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

    public function recentCourses(User $user, int $limit = 5, OrderDirection $order = OrderDirection::Desc): Collection
    {
        return $this
        ->activeCourses($user)
        ->wherePivotNotNull('last_accessed_at')
        ->orderByPivot('last_accessed_at', $order->value)
        ->limit($limit)
        ->get();
    }
}