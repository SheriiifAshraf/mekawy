<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Http\Requests\CourseRequest;

use Illuminate\Http\Request;

class CourseController extends Controller
{
    public function index()
    {
        $courses = Course::withCount(['subscriptions' => function ($query) {
            $query->where('status', 1);
        }])
            ->with(['subscriptions' => function ($query) {
                $query->where('status', 1)->with('student');
            }])
            ->get();

        return view('back.pages.courses.index', compact('courses'));
    }

    public function create()
    {
        return view('back.pages.courses.create');
    }

    public function store(CourseRequest $request)
    {
        $data = $request->validated();

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('courses', 'public');
        }

        Course::create($data);

        return redirect()->route('courses.index')->with('message', 'تم إضافة الكورس بنجاح!');
    }

    public function show(Course $course)
    {
        return view('courses.show', compact('course'));
    }

    public function edit($id)
    {
        $course = Course::findOrFail($id);
        return view('back.pages.courses.edit', compact('course'));
    }

    public function update(CourseRequest $request, $id)
    {
        $course = Course::findOrFail($id);

        $data = $request->validated();

        if ($request->hasFile('image')) {
            if ($course->image) {
                \Storage::disk('public')->delete($course->image);
            }
            $data['image'] = $request->file('image')->store('courses', 'public');
        }

        $data['free'] = $request->has('free') ? 1 : 0;
        $course->update($data);

        return redirect()->route('courses.index')->with('message', 'تم تعديل الكورس بنجاح!');
    }


    public function delete($id)
    {
        $course = Course::find($id);
        $course->delete();
        return redirect()->route('courses.index')->with('message', 'تم حذف الكورس بنجاح!');
    }
}
