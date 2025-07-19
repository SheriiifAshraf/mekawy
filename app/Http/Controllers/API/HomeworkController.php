<?php

namespace App\Http\Controllers\API;

use Carbon\Carbon;
use App\Models\Student;
use App\Models\Homework;
use Illuminate\Http\Request;
use App\Models\HomeworkAttempt;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\HomeworkResource;
use App\Http\Resources\API\HomeworksResource;

class HomeworkController extends Controller
{
    public function homeworks()
    {
        $student = Auth::user();

        $homeworks = Homework::whereHas('course.students', function ($query) use ($student) {
            $query->where('students.id', $student->id);
        })->whereDoesntHave('attempts', function ($query) use ($student) {
            $query->where('student_id', $student->id)->where('completed', true);
        })->get();

        $filteredHomeworks = $homeworks->filter(function ($homework) {
            return $this->isEnrolledInCourse($homework);
        });

        return HomeworksResource::collection($filteredHomeworks);
    }

    public function show(Homework $homework)
    {
        if (!$this->isEnrolledInCourse($homework)) {
            return response()->json(['message' => 'You are not enrolled in this course.'], 403);
        }

        $now = Carbon::now();
        if ($now->lt($homework->from_date)) {
            return response()->json(['message' => 'Homework is not available.'], 403);
        }

        $questions = $homework->questions()->with('answers')->get();
        return new HomeworkResource($homework);
    }

    public function attempt(Request $request, Homework $homework)
    {
        if (!$this->isEnrolledInCourse($homework)) {
            return response()->json(['message' => 'You are not enrolled in this course.'], 403);
        }

        $student = Auth::user();

        $attempt = HomeworkAttempt::where('student_id', $student->id)
            ->where('homework_id', $homework->id)
            ->first();

        if ($attempt && $attempt->completed) {
            return response()->json(['message' => 'You have already completed this homework.'], 403);
        }

        if (!$attempt) {
            $attempt = new HomeworkAttempt();
            $attempt->student_id = $student->id;
            $attempt->homework_id = $homework->id;
            $attempt->started_at = Carbon::now();
        }

        $marks = 0;
        $studentAnswers = [];

        foreach ($request->input('answers') as $question_id => $answer_id) {
            $question = $homework->questions()->find($question_id);

            if ($question) {
                if ($question->answers()->where('id', $answer_id)->where('is_correct', true)->exists()) {
                    $marks++;
                }
                $studentAnswers[$question_id] = $answer_id;
            }
        }

        $attempt->answers = json_encode($studentAnswers);
        $attempt->marks = $marks;
        $attempt->completed = true;
        $attempt->completed_at = Carbon::now();
        $attempt->save();

        return response()->json(['message' => 'Homework completed.', 'answers' => $studentAnswers]);
    }

    public function result(Homework $homework)
    {
        if (!$this->isEnrolledInCourse($homework)) {
            return response()->json(['message' => 'You are not enrolled in this course.'], 403);
        }

        $student = Auth::user();

        $attempt = HomeworkAttempt::where('student_id', $student->id)
            ->where('homework_id', $homework->id)
            ->first();

        if (!$attempt) {
            return response()->json(['message' => 'No attempt found for this homework.'], 404);
        }

        $questions = $homework->questions()->with('answers')->get();
        $studentAnswers = json_decode($attempt->answers, true);

        $data = $questions->map(function ($question) use ($studentAnswers) {
            $correctAnswers = $question->answers->filter(function ($answer) {
                return $answer->is_correct;
            })->pluck('answer');

            $studentAnswerId = $studentAnswers[$question->id] ?? null;
            $studentAnswerText = null;
            if ($studentAnswerId) {
                $studentAnswer = $question->answers->find($studentAnswerId);
                if ($studentAnswer) {
                    $studentAnswerText = $studentAnswer->answer;
                }
            }

            return [
                'question' => $question->question,
                'correct_answers' => $correctAnswers,
                'student_answer' => $studentAnswerText,
            ];
        });

        $homework_marks = $questions->count();

        return response()->json([
            'homework_name' => $homework->name,
            'Answers' => $data,
            'homework_marks' => $homework_marks,
            'student_marks' => $attempt->marks,
        ]);
    }



    public function completedHomeworks()
    {
        $student = Auth::user();

        $completedHomeworks = HomeworkAttempt::where('student_id', $student->id)
            ->where('completed', true)
            ->with('homework.course')
            ->get();

        $response = $completedHomeworks->map(function ($attempt) {
            return [
                'homework_id' => $attempt->homework->id,
                'homework_name' => $attempt->homework->name,
                'course_name' => $attempt->homework->course->name,
            ];
        });

        return response()->json($response);
    }

    public function isEnrolledInCourse($homework)
    {
        $student = Auth::user();
        return $student->subscriptions()->where('course_id', $homework->course_id)->where('status', 1)->exists();
    }
}
