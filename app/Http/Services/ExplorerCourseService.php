<?php

namespace App\Http\Services;

use App\Models\Course;
use Illuminate\Database\Eloquent\Collection;

class ExplorerCourseService
{
    /**
     * * @return Collection
     */
    public static function execute(): Collection
    {
        return Course::with(['services'])
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
