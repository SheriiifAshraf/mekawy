<?php

namespace App\Http\Controllers;

use App\Http\Requests\LessonRequest;
use App\Models\Lesson;
use App\Models\Course;
use Illuminate\Http\Request;

class LessonController extends Controller
{
    public function index(Course $course)
    {
        $lessons = $course->lessons;
        return view('back.pages.lessons.index', compact('course', 'lessons'));
    }

    public function create(Course $course)
    {
        return view('back.pages.lessons.create', compact('course'));
    }

    public function store(LessonRequest $request, Course $course)
    {
        $course->lessons()->create($request->validated());
        return redirect()->route('courses.lessons.index', $course)->with('message', 'تم إضافة الدرس بنجاح!');
    }

    public function show(Course $course, Lesson $lesson)
    {
        return view('lessons.show', compact('course', 'lesson'));
    }

    public function edit(Lesson $lesson)
    {
        return view('back.pages.lessons.edit', compact('lesson'));
    }

    public function update(LessonRequest $request, Lesson $lesson)
    {
        $lesson->update($request->validated());
        return redirect()->route('courses.lessons.index', $lesson->course_id)->with('message', 'تم تعديل الدرس بنجاح!');
    }

    public function delete(Lesson $lesson)
    {
        $courseId = $lesson->course_id;
        $lesson->delete();
        return redirect()->route('courses.lessons.index', $courseId)->with('message', 'تم حذف الدرس بنجاح!');
    }
}
