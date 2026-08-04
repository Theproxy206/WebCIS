<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProfilePictureUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
{
    return [
        'image' => [
            'required',
            'image',
            'mimes:jpg,jpeg,png,webp',
            'max:2048',
            'dimensions:min_width=256,min_height=256',
        ],
    ];
}
}