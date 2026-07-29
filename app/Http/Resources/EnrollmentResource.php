<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EnrollmentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'token' => $this->cou_token,
            'code' => $this->cou_code,
            'title' => $this->cou_title,
            'short_title' => $this->cou_short_title,
            'description' => $this->cou_description,
            'status' => $this->enrollment->status,
            'joined_at' => $this->enrollment->joined_at,
            'completed_at' => $this->enrollment->completed_at,
            'last_accessed_at' => $this->enrollment->last_accessed_at,
            'icon' => $this->cou_path_icon,
        ];
    }
}
