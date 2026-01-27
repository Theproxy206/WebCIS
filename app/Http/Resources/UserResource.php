<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
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
            'name' => $this->user_name,
            'surname' => $this->user_surname,
            'second_surname' => $this->user_second_surname,
            'type' => $this->user_type->name,
            'created_at' => $this->created_at?->toIso8601String(),
        ];
    }
}
