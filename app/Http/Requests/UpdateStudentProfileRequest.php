<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateStudentProfileRequest extends FormRequest
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
            'phone' => 'unique:students,phone,' . auth('student')->id(),
            'father_phone' => 'different:phone',
            'password' => 'confirmed|string|min:8',
            'email' => 'email|unique:students,email,' . auth('student')->id(),
        ];
    }
}
