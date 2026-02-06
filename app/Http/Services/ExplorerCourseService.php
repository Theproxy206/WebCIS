<?php

namespace App\Http\Services;

use App\Models\Course;
use Illuminate\Database\Eloquent\Collection;

class ExplorerCourseService
{
    public static function execute($request): Collection
    {
        return Course::with(['services'])
        ->when($request->category, function ($query, $category) {
            return $query->where('category_id', $category);
        })
            ->when($request->subject, function ($query, $subject) {
                return $query->where('subject_id', $subject);
            })
            ->when($request->date, function ($query, $date) {
                return $query->whereDate('created_at', $date);
            })
            ->when($request->code, function ($query, $code) {
                return $query->where('cou_code', 'LIKE', "%$code%");
            })
            ->get([
                'cou_token',
                'cou_title',
                'cou_short_title',
                'cou_description',
                'cou_code',
                'cou_content',
                'cou_path_icon'
            ]);
    }
}
