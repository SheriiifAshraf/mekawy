<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LessonRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'حقل اسم الدرس مطلوب.',
            'name.string' => 'يجب أن يكون اسم الدرس نصًا.',
            'name.max' => 'يجب ألا يتجاوز اسم الدرس 255 حرفًا.',
            'description.string' => 'يجب أن يكون وصف الدرس نصًا.',
        ];
    }
}
