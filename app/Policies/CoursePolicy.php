<?php

namespace App\Policies;

use App\Enums\CourseRole;
use App\Enums\CourseStatus;
use App\Enums\UserType;
use App\Models\Course;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class CoursePolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Course $course): bool
    {
        return true;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->hasPermission('create-course');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Course $course): bool
    {
        return $user->hasPermission('create-course')
            && $user->courses()
            ->where('cou_id', $course->cou_id)
            ->wherePivot('role', CourseRole::Owner)
            ->exists();
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Course $course): bool
    {
        return false;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Course $course): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function destroy(User $user, Course $course): bool
    {
        return (
            $user->user_type === UserType::Admin 
            && 
            $user->hasPermission('delete-course')
        )
        || 
        (
            $user->hasPermission('create-course') 
            && 
            $user->courses()
            ->where('cou_id', $course->cou_id)
            ->wherePivot('role', CourseRole::Owner)
            ->exists()
            &&
            (
                $course->cou_status === CourseStatus::Draft
                ||
                $course->cou_status === CourseStatus::Archived
            )
        );
    }

    public function storeIcon(User $user): bool
    {
        return $user->hasPermission('create-course');
    }

    public function sendForApproval(User $user, Course $course): bool
    {
        return $user->hasPermission('create-course')
        && $user->courses()
        ->where('cou_id', $course->cou_id)
        ->wherePivot('role', CourseRole::Owner)
        ->exists();
    }

    public function approve(User $user): bool
    {
        return $user->user_type === UserType::Admin && $user->hasPermission('approve-course');
    }

    public function reject(User $user): bool
    {
        return $user->user_type === UserType::Admin && $user->hasPermission('approve-course');
    }

    public function publish(User $user, Course $course): bool
    {
        return $user->hasPermission('publish-course')
        && (
            $user->courses()
                ->where('cou_id', $course->cou_id)
                ->wherePivot('role', CourseRole::Owner)
                ->exists()
            || $user->user_type === UserType::Admin
        );
    }

    public function updateIcon(User $user, Course $course): bool
    {
        return $user->hasPermission('create-course')
            && $user->courses()
            ->where('cou_id', $course->cou_id)
            ->wherePivot('role', CourseRole::Owner)
            ->exists();
    }
}
