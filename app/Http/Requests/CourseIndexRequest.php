<?php

namespace App\Http\Requests;

use App\Enums\CourseStatus;
use App\Enums\OrderDirection;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Enum;

class CourseIndexRequest extends FormRequest
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
            'page' => [
                'sometimes',
                'integer',
                'min:1',
            ],

            'per_page' => [
                'sometimes',
                'integer',
                'min:1',
                'max:50',
            ],

            'search' => [
                'sometimes',
                'string',
                'max:100',
            ],

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

            'created_from' => [
                'sometimes',
                'date',
                'before_or_equal:created_to',
            ],

            'created_to' => [
                'sometimes',
                'date',
                'after_or_equal:created_from',
            ],

            'sort' => [
                'sometimes',
                Rule::in([
                    'title',
                    'created_at',
                    'updated_at',
                ]),
            ],

            'order' => [
                'sometimes',
                new Enum(OrderDirection::class),
            ],
        ];
    }
}
