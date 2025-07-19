<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class VideoRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'link' => 'nullable|url',
            'publish_at' => 'required|date|after_or_equal:now',
            'pdf' => 'nullable|file|mimes:pdf|max:1024000', // 1 GB
            'image' => 'nullable|image|max:2048', // 2 MB
            'duration_minutes' => 'nullable|integer|min:0', // New validation rule for duration
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'حقل اسم الفيديو مطلوب.',
            'name.string' => 'يجب أن يكون اسم الفيديو نصًا.',
            'name.max' => 'يجب ألا يتجاوز اسم الفيديو 255 حرفًا.',
            'link.url' => 'يجب أن يكون رابط الفيديو رابطًا صحيحًا.',
            'publish_at.required' => 'حقل توقيت النشر مطلوب.',
            'publish_at.date' => 'يجب أن يكون توقيت النشر تاريخًا صحيحًا.',
            'publish_at.after_or_equal' => 'يجب أن يكون توقيت النشر بعد أو يساوي الوقت الحالي.',
            'pdf.file' => 'يجب أن يكون الملف من نوع PDF.',
            'pdf.mimes' => 'يجب أن يكون الملف من نوع PDF.',
            'pdf.max' => 'يجب ألا يتجاوز حجم الملف 1 جيجابايت.',
            'image.image' => 'يجب أن تكون الصورة من نوع صورة.',
            'image.max' => 'يجب ألا يتجاوز حجم الصورة 2048 كيلوبايت.',
            'duration_minutes.integer' => 'يجب أن تكون مدة الفيديو بالدقائق رقمًا صحيحًا.',
            'duration_minutes.min' => 'مدة الفيديو بالدقائق يجب أن تكون على الأقل 0.',
        ];
    }
}
