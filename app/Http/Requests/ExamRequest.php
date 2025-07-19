<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Carbon\Carbon;

class ExamRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'duration' => 'required|integer',
            'from_date' => 'required',
            'instructions' => 'nullable|string',
            'status' => 'boolean',
            'question_ids' => 'required|array',
            'question_ids.*' => 'exists:questions,id',
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'اسم الامتحان مطلوب.',
            'name.string' => 'اسم الامتحان يجب أن يكون نصًا.',
            'name.max' => 'اسم الامتحان يجب ألا يتجاوز 255 حرفًا.',
            'duration.required' => 'المدة مطلوبة.',
            'duration.integer' => 'المدة يجب أن تكون رقمًا صحيحًا.',
            'from_date.required' => 'من تاريخ مطلوب.',
            'instructions.string' => 'التعليمات يجب أن تكون نصًا.',
            'status.boolean' => 'الحالة يجب أن تكون إما نشط أو غير نشط.',
            'question_ids.required' => 'يجب اختيار الأسئلة.',
            'question_ids.array' => 'يجب أن تكون الأسئلة في شكل قائمة.',
            'question_ids.*.exists' => 'السؤال المحدد غير موجود.',
        ];
    }
}
