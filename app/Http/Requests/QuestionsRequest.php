<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class QuestionsRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'question' => 'required|string|max:255',
            'explanation' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ];
    }

    public function messages()
    {
        return [
            'question.required' => 'حقل السؤال مطلوب.',
            'question.string' => 'حقل السؤال يجب أن يكون نصًا.',
            'question.max' => 'حقل السؤال لا يجب أن يتجاوز 255 حرفًا.',
            'explanation.string' => 'حقل التوضيح يجب أن يكون نصًا.',
            'image.image' => 'يجب أن يكون الملف صورة.',
            'image.mimes' => 'يجب أن يكون نوع الصورة jpeg, png, jpg, gif, أو svg.',
            'image.max' => 'يجب ألا تتجاوز الصورة حجم 2048 كيلوبايت.',
        ];
    }
}
