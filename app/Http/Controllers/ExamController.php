<?php

namespace App\Http\Controllers;

use App\Models\Exam;
use App\Models\Course;
use App\Models\Question;
use App\Models\ExamAttempt;
use Illuminate\Http\Request;
use App\Http\Requests\ExamRequest;

class ExamController extends Controller
{
    public function index(Course $course)
    {
        $exams = $course->exams;
        return view('back.pages.exams.index', compact('course', 'exams'));
    }

    public function create(Course $course)
    {
        $questions = Question::all();
        return view('back.pages.exams.create', compact('course', 'questions'));
    }

    public function store(ExamRequest $request, Course $course)
    {
        $exam = $course->exams()->create($request->except('question_ids'));

        if ($request->has('question_ids')) {
            $exam->questions()->sync($request->input('question_ids'));
        }

        return redirect()->route('courses.exams.index', $course)->with('message', 'تم إضافة الامتحان بنجاح!');
    }


    public function edit(Exam $exam)
    {
        $questions = Question::all();
        return view('back.pages.exams.edit', compact('exam', 'questions'));
    }

    public function update(ExamRequest $request, $id)
    {
        $exam = Exam::findOrFail($id);

        $exam->update($request->except('question_ids'));
        if ($request->has('question_ids')) {
            $exam->questions()->sync($request->input('question_ids', []));
        } else {
            $exam->questions()->detach();
        }

        return redirect()->route('courses.exams.index', $exam->course_id)
            ->with('message', 'تم تعديل الواجب بنجاح!');
    }


    public function destroy(Exam $exam)
    {
        $courseId = $exam->course_id;
        $exam->delete();
        return redirect()->route('courses.exams.index', $courseId)->with('message', 'تم حذف الامتحان بنجاح!');
    }

    public function pdf(Exam $exam)
    {
        $questions = $exam->questions()->with('answers')->get();
        return view('back.pages.exams.pdf', compact('exam', 'questions'));
    }

    public function results(Exam $exam)
    {
        $attempts = ExamAttempt::where('exam_id', $exam->id)->with('student')->get();
        $questions = $exam->questions()->get();
        $exam_marks = $questions->count();
        return view('back.pages.exams.results', compact('exam', 'attempts', 'exam_marks'));
    }
}
