<?php

namespace App\Http\Controllers\API;

use Carbon\Carbon;
use App\Models\Exam;
use App\Models\Student;
use App\Models\ExamAttempt;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\API\ExamResource;
use App\Http\Resources\API\ExamsResource;

class ExamController extends Controller
{
    public function exams()
    {
        $student = Auth::user();

        $exams = Exam::where(function ($query) use ($student) {
            $query->whereHas('course', function ($subQuery) use ($student) {
                $subQuery->whereHas('students', function ($nestedQuery) use ($student) {
                    $nestedQuery->where('students.id', $student->id);
                })->orWhere('free', 1);
            });
        })->whereDoesntHave('attempts', function ($query) use ($student) {
            $query->where('student_id', $student->id)->where('completed', true);
        })->where('from_date', '<=', Carbon::now())
            ->get();

        $filteredExams = $exams->filter(function ($exam) use ($student) {
            return $exam->course->free == 1 || $this->isEnrolledInCourse($exam);
        });

        return ExamsResource::collection($filteredExams);
    }


    public function show(Exam $exam)
    {
        if (!$exam->course->free && !$this->isEnrolledInCourse($exam)) {
            return response()->json(['message' => 'You are not enrolled in this course.'], 403);
        }

        $now = Carbon::now();
        if ($now->lt($exam->from_date)) {
            return response()->json(['message' => 'Exam is not available yet.'], 403);
        }


        $questions = $exam->questions()->with('answers')->get();
        return new ExamResource($exam);
    }


    public function attempt(Request $request, Exam $exam)
    {
        if (!$exam->course->free && !$this->isEnrolledInCourse($exam)) {
            return response()->json(['message' => 'You are not enrolled in this course.'], 403);
        }

        $student = Auth::user();

        $attempt = ExamAttempt::where('student_id', $student->id)
            ->where('exam_id', $exam->id)
            ->first();

        if ($attempt && $attempt->completed) {
            return response()->json(['message' => 'You have already completed this exam.'], 403);
        }

        if (!$attempt) {
            $attempt = new ExamAttempt();
            $attempt->student_id = $student->id;
            $attempt->exam_id = $exam->id;
            $attempt->started_at = Carbon::now();
        }

        $marks = 0;
        $studentAnswers = [];

        foreach ($request->input('answers') as $question_id => $answer_id) {
            $question = $exam->questions()->find($question_id);

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

        return response()->json(['message' => 'Exam completed.', 'answers' => $studentAnswers]);
    }

    public function result(Exam $exam)
    {
        if (!$exam->course->free && !$this->isEnrolledInCourse($exam)) {
            return response()->json(['message' => 'You are not enrolled in this course.'], 403);
        }

        $student = Auth::user();

        $attempt = ExamAttempt::where('student_id', $student->id)
            ->where('exam_id', $exam->id)
            ->first();

        if (!$attempt) {
            return response()->json(['message' => 'No attempt found for this exam.'], 404);
        }

        $questions = $exam->questions()->with('answers')->get();
        $studentAnswers = json_decode($attempt->answers, true);

        $data = $questions->map(function ($question) use ($studentAnswers) {
            $correctAnswers = $question->answers->filter(function ($answer) {
                return $answer->is_correct;
            })->pluck('answer');

            $studentAnswerId = $studentAnswers[$question->id] ?? null;
            $studentAnswerText = null;
            $isCorrect = false;

            if ($studentAnswerId) {
                $studentAnswer = $question->answers->find($studentAnswerId);
                if ($studentAnswer) {
                    $studentAnswerText = $studentAnswer->answer;
                    $isCorrect = $correctAnswers->contains($studentAnswer->answer);
                }
            }

            $allAnswers = $question->answers->pluck('answer');

            return [
                'question' => $question->question,
                'all_answers' => $allAnswers,
                'correct_answers' => $correctAnswers,
                'student_answer' => $studentAnswerText,
                'is_correct' => $isCorrect,
            ];
        });

        $exam_marks = $questions->count();
        $student_marks = $attempt->marks;

        $studentsAttempts = ExamAttempt::where('exam_id', $exam->id)->get();

        $rankedStudents = $studentsAttempts->sortByDesc('marks');

        $studentRank = 1;
        $previousMarks = null;
        $rankCounter = 1;

        foreach ($rankedStudents as $index => $attempt) {
            if ($attempt->marks == $previousMarks) {
                $studentRank = $rankCounter;
            } else {
                $studentRank = $index + 1;
            }

            if ($attempt->marks === $student_marks && $attempt->student_id == $student->id) {
                $studentRank = $rankCounter;
            }

            $previousMarks = $attempt->marks;
            $rankCounter++;
        }

        return response()->json([
            'exam_name' => $exam->name,
            'Answers' => $data,
            'exam_marks' => $exam_marks,
            'student_marks' => $student_marks,
            'student_rank' => $studentRank,
        ]);
    }




    public function completedExams()
    {
        $student = Auth::user();

        $completedExams = ExamAttempt::where('student_id', $student->id)
            ->where('completed', true)
            ->with('exam.course')
            ->get();

        $response = $completedExams->map(function ($attempt) {
            return [
                'exam_id' => $attempt->exam->id,
                'exam_name' => $attempt->exam->name,
                'course_name' => $attempt->exam->course->name,
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
