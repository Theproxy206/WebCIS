<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CourseCreateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'code' => strtoupper($this->input('code')),
        ]);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'code' => ['required', 'alpha_dash', 'string', 'min:3', 'max:14', 'unique:courses,cou_code'],
            'title' => ['required', 'min:1', 'max:200', 'string'],
            'short_title' => ['required', 'min:1', 'max:80', 'string'],
            'description' => ['sometimes', 'nullable', 'min:1', 'max:300', 'string'],
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
            'icon' => ['sometimes', 'nullable', 'string', 'max:255']
        ];
    }
}
