<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateMaterialRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'mat_title'            => 'sometimes|string|max:80',
            'mat_publication_date' => 'sometimes|date',
            'mat_code'             => 'sometimes|string|max:14',
            'mat_description'      => 'nullable|string|max:300',
        ];
    }
}
