<?php

namespace App\Http\Requests;

use App\Enums\UserType;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

class RegisterRequest extends FormRequest
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
            'token' => 'required',
            'email' => 'required|email|max:255',
            'username' => 'required|string|max:40',
            'password' => 'required|string|min:8',
            'names' => 'required|string',
            'surname' => 'required|string',
            'second_surname' => 'string',
            'type' => ['required', new Enum(UserType::class)],
        ];
    }
}
