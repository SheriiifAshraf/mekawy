<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Homework;
use App\Models\Question;
use Illuminate\Http\Request;
use App\Models\HomeworkAttempt;
use App\Http\Requests\HomeworkRequest;

class HomeworkController extends Controller
{
    public function index(Course $course)
    {
        $homeworks = $course->homeworks;
        return view('back.pages.homeworks.index', compact('course', 'homeworks'));
    }

    public function create(Course $course)
    {
        $questions = Question::all();
        return view('back.pages.homeworks.create', compact('course', 'questions'));
    }

    public function store(HomeworkRequest $request, Course $course)
    {
        $homework = $course->homeworks()->create($request->except('question_ids'));

        if ($request->has('question_ids')) {
            $homework->questions()->sync($request->input('question_ids'));
        }

        return redirect()->route('courses.homeworks.index', $course)->with('message', 'تم إضافة الواجب بنجاح!');
    }



    public function edit(Homework $homework)
    {
        $questions = Question::all();
        return view('back.pages.homeworks.edit', compact('homework', 'questions'));
    }


    public function update(HomeworkRequest $request, $id)
    {
        $homework = Homework::findOrFail($id);

        $homework->update($request->except('question_ids'));
        if ($request->has('question_ids')) {
            $homework->questions()->sync($request->input('question_ids', []));
        } else {
            $homework->questions()->detach();
        }

        return redirect()->route('courses.homeworks.index', $homework->course_id)
            ->with('message', 'تم تعديل الواجب بنجاح!');
    }



    public function destroy(Homework $homework)
    {
        $courseId = $homework->course_id;
        $homework->delete();
        return redirect()->route('courses.homeworks.index', $courseId)->with('message', 'تم حذف الواجب بنجاح!');
    }

    public function pdf(Homework $homework)
    {
        $questions = $homework->questions()->with('answers')->get();
        return view('back.pages.homeworks.pdf', compact('homework', 'questions'));
    }

    public function results(Homework $homework)
    {
        $attempts = HomeworkAttempt::where('homework_id', $homework->id)->with('student')->get();
        $questions = $homework->questions()->get();
        $homework_marks = $questions->count();
        return view('back.pages.homeworks.results', compact('homework', 'attempts', 'homework_marks'));
    }
}
