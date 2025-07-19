<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateCodeRequest extends FormRequest
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
            'student_id' => 'nullable|exists:students,id',
            'period' => 'required|integer',
            'type' => 'required|string|in:course,lesson,video,exam,homework',
            'course_id' => 'nullable|required_if:type,course|exists:courses,id',
            'lesson_id' => 'nullable|required_if:type,lesson|exists:lessons,id',
            'video_id' => 'nullable|required_if:type,video|exists:videos,id',
        ];
    }
}
