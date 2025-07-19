<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class QuestionsRequest extends FormRequest
{
    public function rules()
    {
        return [
            'question' => 'required|string',
            'explanation' => 'nullable|string',
            'question_image' => 'nullable|image',
            'answers' => 'nullable|array',
            'answers.*.answer' => 'required_with:answers|string',
            'answers.*.is_correct' => 'nullable|boolean',
            'answers.*.answer_image' => 'nullable|image',
        ];
    }

    public function messages()
    {
        return [
            'question.required' => 'يجب إدخال السؤال',
            'answers.*.answer.required_with' => 'يجب إدخال الإجابة',
        ];
    }
}
