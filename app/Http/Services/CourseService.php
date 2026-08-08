<?php

namespace App\Http\Services;

use App\Enums\OrderDirection;
use App\Enums\CourseStatus;
use App\Models\Course;
use Illuminate\Pagination\LengthAwarePaginator;

class CourseService {
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
}