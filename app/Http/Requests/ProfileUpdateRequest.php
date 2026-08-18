<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProfileUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'username' => [
                'sometimes',
                'string',
                'max:40',
                Rule::unique('users', 'user_username')
                    ->ignore($this->user()->user_id, 'user_id'),
            ],

            'description' => [
                'sometimes',
                'nullable',
                'string',
                'max:300',
            ],

            'name' => [
                'sometimes',
                'string',
                'max:50',
            ],

            'surname' => [
                'sometimes',
                'string',
                'max:40',
            ],

            'second_surname' => [
                'sometimes',
                'nullable',
                'string',
                'max:40',
            ],
        ];
    }
}