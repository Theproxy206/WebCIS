<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class LessonResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->les_Serial,
            'title' => $this->les_title,
            'short_title' => $this->les_short_title,
            'order' => $this->les_order,
            'content' => $this->les_content
        ];
    }
}
