<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CourseRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'free' => 'boolean',
            'description' => 'nullable|string',
            'image' => 'nullable|image',
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'حقل اسم الكورس مطلوب.',
            'name.string' => 'يجب أن يكون اسم الكورس نصًا.',
            'name.max' => 'يجب ألا يتجاوز اسم الكورس 255 حرفًا.',
            'price.required' => 'حقل سعر الكورس مطلوب.',
            'price.numeric' => 'يجب أن يكون سعر الكورس رقمًا.',
            'price.min' => 'يجب أن يكون سعر الكورس قيمة موجبة.',
            'description.string' => 'يجب أن يكون وصف الكورس نصًا.',
        ];
    }
}

