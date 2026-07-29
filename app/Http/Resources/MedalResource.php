<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MedalResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'name' => $this->med_name,
            'description' => $this->med_description,
            'obtained_at' => $this->pivot->obtained_at,
            'image' => $this->med_path_image,
        ];
    }
}
