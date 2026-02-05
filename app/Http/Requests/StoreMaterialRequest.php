<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreMaterialRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'mat_title'            => 'required|string|max:80',
            'mat_publication_date' => 'required|date',
            'mat_code'             => 'required|string|max:14',
            'mat_description'      => 'nullable|string|max:300',
        ];
    }
}
