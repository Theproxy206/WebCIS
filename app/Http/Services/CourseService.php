<?php

namespace App\Http\Services;

use App\Enums\CourseRole;
use App\Enums\OrderDirection;
use App\Enums\CourseStatus;
use App\Exceptions\Courses\CourseUpdateError;
use App\Exceptions\Courses\InvalidCourseStatusTransition;
use App\Models\Category;
use App\Models\Course;
use App\Models\Subject;
use App\Models\User;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use function PHPUnit\Framework\throwException;

class CourseService {
    public function __construct(
        private readonly StorageService $store
    ) {}

    public function paginate(array $filters = []): LengthAwarePaginator
    {
        $query = Course::query()->where('cou_status', CourseStatus::Published)->with([
            'categories',
            'subjects',
        ]);

        if (!empty($filters['search'])) {
            $search = $filters['search'];

            $query->where(function ($query) use ($search) {
                $query
                    ->where('cou_title', 'like', "%{$search}%")
                    ->orWhere('cou_description', 'like', "%{$search}%")
                    ->orWhere('cou_code', 'like', "%{$search}%");
            });
        }

        if (!empty($filters['categories'])) {
            $query->whereHas('categories', function ($query) use ($filters) {
                $query->whereIn('cat_code', $filters['categories']);
            });
        }

        if (!empty($filters['subjects'])) {
            $query->whereHas('subjects', function ($query) use ($filters) {
                $query->whereIn('sub_code', $filters['subjects']);
            });
        }

        if (!empty($filters['created_from'])) {
            $query->where(
                'created_at',
                '>=',
                $filters['created_from']
            );
        }

        if (!empty($filters['created_to'])) {
            $query->where(
                'created_at',
                '<=',
                $filters['created_to']
            );
        }

        $sortColumns = [
            'title' => 'cou_title',
            'created_at' => 'created_at',
            'updated_at' => 'updated_at',
        ];

        $sort = $sortColumns[$filters['sort'] ?? 'created_at'];
        $order = $filters['order'] ?? OrderDirection::Desc;

        $query->orderBy($sort, $order->value);

        return $query->paginate(
            perPage: $filters['per_page'] ?? 15,
            page: $filters['page'] ?? 1,
        );
    }

    /**
     * Search for one specific Course using its code
     * 
     * @param string $code
     * @return Course
     */
    public function getCourse(string $code): Course
    {
        return Course::where('cou_code', $code)->with([
            'categories',
            'subjects',
            'lessons' => function ($query) {
                $query->orderBy('les_order');
            },
        ])->firstOrFail();
    }

    /**
     * Creates a course
     * 
     * @param array $data
     * @return Course
     */
    public function createCourse(User $user, array $data): Course
    {
        return DB::transaction(function () use ($user, $data) {
            $course = Course::create([
                'cou_title' => $data['title'],
                'cou_short_title' => $data['short_title'],
                'cou_code' => $data['code'],
                'cou_description' => $data['description'] ?? null,
                'cou_path_icon' => $data['icon'] ?? null,
                'cou_status' => CourseStatus::Draft,
            ]);

            $categories = Category::whereIn(
                'cat_code',
                $data['categories'] ?? []
            )->pluck('cat_serial');

            $subjects = Subject::whereIn(
                'sub_code',
                $data['subjects'] ?? []
            )->pluck('sub_serial');

            $course->categories()->attach($categories);
            $course->subjects()->attach($subjects);

            $user->courses()->attach(
                $course,
                [
                    'role' => CourseRole::Owner,
                    'created_at' => Carbon::now(),
                ]
            );

            return $course->load([
                'categories',
                'subjects',
            ]);
        });
    }

    /**
     * Sends a course to approval.
     * 
     * @param string $code Public code of the course.
     * @throws InvalidCourseStatusTransition
     * @return Course
     */
    public function seekApproval(string $code): Course
    {
        $course = Course::where('cou_code', $code)->firstOrFail();

        if ($course->cou_status !== CourseStatus::Draft) {
            throw new InvalidCourseStatusTransition(
                'Only draft courses can be send to approval.'
            );
        }

        $course->cou_status = CourseStatus::PendingReview;
        $course->save();

        return $course->refresh();
    }

    /**
     * Approves a course.
     * 
     * @param string $code Public code of the course.
     * @throws InvalidCourseStatusTransition
     * @return Course
     */
    public function approve(string $code): Course
    {
        $course = Course::where('cou_code', $code)->firstOrFail();

        if ($course->cou_status !== CourseStatus::PendingReview) {
            throw new InvalidCourseStatusTransition(
                'Only courses waiting for review can be approved.'
            );
        }

        $course->cou_status = CourseStatus::Approved;
        $course->save();

        return $course->refresh();
    }

    /**
     * Rejects a course and set it to a draft.
     * 
     * @param string $code Public code of the course.
     * @throws InvalidCourseStatusTransition
     * @return Course
     */
    public function reject(string $code): Course
    {
        $course = Course::where('cou_code', $code)->firstOrFail();

        if ($course->cou_status !== CourseStatus::PendingReview) {
            dd($course);
            
            throw new InvalidCourseStatusTransition(
                'Only courses waiting for review can be rejected.'
            );
        }

        $course->cou_status = CourseStatus::Draft;
        $course->save();

        return $course->refresh();
    }

    /**
     * Publish a course.
     * 
     * @param string $code Public code of the course.
     * @throws InvalidCourseStatusTransition
     * @return Course
     */
    public function publish(string $code): Course
    {
        $course = Course::where('cou_code', $code)->firstOrFail();

        if ($course->cou_status !== CourseStatus::Approved) {
            throw new InvalidCourseStatusTransition(
                'Only approved courses can be published.'
            );
        }

        $course->cou_status = CourseStatus::Published;
        $course->save();

        return $course->refresh();
    }

    public function updateCourse(array $data, string $code): Course
    {
        $oldIcon = null;
        $newIcon = null;

        try {
            $course = DB::transaction(function () use ($data, $code, &$oldIcon, &$newIcon) {
                $course = Course::where('cou_code', $code)->firstOrFail();
                $oldIcon = $course->cou_path_icon;
                $newIcon = $data['icon'] ?? null;
                
                if (array_key_exists('icon', $data)) {
                    $course->cou_path_icon = $data['icon'];
                }

                $course->cou_code = $data['code'] ?? $course->cou_code;
                $course->cou_title = $data['title'] ?? $course->cou_title;
                $course->cou_short_title = $data['short_title'] ?? $course->cou_short_title;
                if (array_key_exists('description', $data)) {
                    $course->cou_description = $data['description'];
                }
                $course->save();

                if (array_key_exists('categories', $data)) {
                    $categories = Category::whereIn(
                        'cat_code',
                        $data['categories']
                    )->pluck('cat_serial');

                    $course->categories()->sync($categories);
                }

                if (array_key_exists('subjects', $data)) {
                    $subjects = Subject::whereIn(
                        'sub_code',
                        $data['subjects']
                    )->pluck('sub_serial');

                    $course->subjects()->sync($subjects);
                }

                return $course->refresh();
            });
        } catch (\Throwable $e) {
            if ($newIcon !== null && $newIcon !== $oldIcon) {
                $this->store->delete($newIcon);
            }

            throw new CourseUpdateError(previous: $e);
        }
        
        if ($oldIcon !== null && $oldIcon !== $newIcon) {
            $this->store->delete($oldIcon);
        }

        return $course->load([
            'categories',
            'subjects'
        ]);
    }

    public function delete(string $code)
    {
        $icon = null;

        DB::transaction(function () use ($code, &$icon) {
            $course = Course::where('cou_code', $code)->firstOrFail();

            $icon = $course->cou_path_icon;

            $course->delete();
        });

        if ($icon !== null) {
            $this->store->delete($icon);
        }
    }
}