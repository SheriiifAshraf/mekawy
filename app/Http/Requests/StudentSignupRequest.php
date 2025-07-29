<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StudentSignupRequest extends FormRequest
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
            'first_name'    => 'required|string',
            'middle_name'   => 'required|string',
            'last_name'     => 'required|string',
            'phone'         => 'required|string|unique:students',
            'father_phone'  => 'required|string|different:phone',
            'password'      => 'required|confirmed|string|min:8',
            'location_id'   => 'required|exists:locations,id',
            'education_stage_id' => 'required|exists:education_stages,id',
            'grade_id' => 'required|exists:grades,id',
        ];
    }
}
