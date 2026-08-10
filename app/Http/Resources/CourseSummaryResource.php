<?php

namespace App\Http\Resources;

use App\Http\Resources\CategoryResource;
use App\Http\Resources\SubjectResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class CourseSummaryResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'code' => $this->cou_code,
            'title' => $this->cou_title,
            'short_title' => $this->cou_short_title,
            'description' => $this->cou_description,
            'categories' => CategoryResource::collection(
                $this->whenLoaded('categories')
            ),
            'subjects' => SubjectResource::collection(
                $this->whenLoaded('subjects')
            ),
            'status' => $this->cou_status,
            'icon' => $this->cou_path_icon ? Storage::disk('public')->url($this->cou_path_icon) : null,
        ];
    }
}