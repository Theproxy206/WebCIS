<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'username' => $this->user_username,
            'email' => $this->user_email,
            'control_number' => $this->user_control_number,
            'name' => $this->user_name,
            'surname' => $this->user_surname,
            'second_surname' => $this->user_second_surname,
            'description' => $this->user_description,
            'type' => $this->user_type->name,
            'profile_picture' => $this->user_path_profile_picture ? Storage::disk('public')->url($this->user_path_profile_picture) : null,
            'banner' => $this->user_path_banner ? Storage::disk('public')->url($this->user_path_banner) : null,
            'created_at' => $this->created_at?->toIso8601String(),
        ];
    }
}
