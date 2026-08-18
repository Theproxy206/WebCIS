<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CourseUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'code' => [
                'sometimes',
                'alpha_dash',
                'string',
                'min:3',
                'max:14',
                Rule::unique('courses', 'cou_code')->ignore($this->route('course'), 'cou_code')
            ],
            'title' => ['sometimes', 'min:1', 'max:200', 'string'],
            'short_title' => ['sometimes', 'min:1', 'max:80', 'string'],
            'description' => ['nullable', 'min:1', 'max:300', 'string'],
            'categories' => [
                'sometimes',
                'array',
                'min:1',
                'max:5',
            ],
            'categories.*' => [
                'string',
                'exists:categories,cat_code',
            ],
            'subjects' => [
                'sometimes',
                'array',
                'min:1',
                'max:5',
            ],
            'subjects.*' => [
                'string',
                'exists:subjects,sub_code',
            ],
            'icon' => ['sometimes', 'string', 'max:255'],
        ];
    }
}
