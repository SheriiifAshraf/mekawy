<?php

namespace App\Http\Controllers;

use App\Models\Question;
use App\Models\ExamAttempt;

use Illuminate\Http\Request;
use App\Http\Requests\QuestionsRequest;

class QuestionsController extends Controller
{
    public function index()
    {
        $questions = Question::all();
        return view('back.pages.questions.index', compact('questions'));
    }

    public function create()
    {
        return view('back.pages.questions.create');
    }

    public function store(QuestionsRequest $request)
    {
        $data = $request->validated();

        $question = Question::create($data);

        if ($request->hasFile('question_image')) {
            $question->addMediaFromRequest('question_image')->toMediaCollection('questions');
        }

        $answers = $request->input('answers', []);

        foreach ($answers as $index => $answerData) {
            $answer = $question->answers()->create([
                'answer' => $answerData['answer'],
                'is_correct' => isset($answerData['is_correct']) ? 1 : 0,
            ]);

            if ($request->hasFile("answers.$index.answer_image")) {
                $answer->addMediaFromRequest("answers.$index.answer_image")->toMediaCollection('answers');
            }
        }

        return redirect()->route('questions.index')->with('message', 'تم إضافة السؤال بنجاح!');
    }


    public function show(Question $question)
    {
        return view('questions.show', compact('question'));
    }

    public function edit($id)
    {
        $question = Question::findOrFail($id);
        return view('back.pages.questions.edit', compact('question'));
    }


    public function update(QuestionsRequest $request, Question $question, $id)
    {
        $question = Question::findOrFail($id);
        $data = $request->validated();

        $question->update($data);

        if ($request->hasFile('question_image')) {
            $question->clearMediaCollection('questions');
            $question->addMediaFromRequest('question_image')->toMediaCollection('questions');
        }

        $answers = $request->input('answers', []);

        foreach ($answers as $index => $answerData) {
            if (isset($question->answers[$index])) {
                $answer = $question->answers[$index];
                $answer->update([
                    'answer' => $answerData['answer'],
                    'is_correct' => isset($answerData['is_correct']) ? 1 : 0,
                ]);

                if ($request->hasFile("answers.$index.answer_image")) {
                    $answer->clearMediaCollection('answers');
                    $answer->addMediaFromRequest("answers.$index.answer_image")->toMediaCollection('answers');
                }
            } else {
                $answer = $question->answers()->create([
                    'answer' => $answerData['answer'],
                    'is_correct' => isset($answerData['is_correct']) ? 1 : 0,
                ]);

                if ($request->hasFile("answers.$index.answer_image")) {
                    $answer->addMediaFromRequest("answers.$index.answer_image")->toMediaCollection('answers');
                }
            }
        }

        $questionId = $question->id;
        $attempts = ExamAttempt::whereNotNull('answers')->get();

        foreach ($attempts as $attempt) {
            $answers = json_decode($attempt->answers, true);

            if (!isset($answers[$questionId])) {
                continue;
            }

            $studentAnswerId = $answers[$questionId];

            $isStillCorrect = $question->answers()
                ->where('id', $studentAnswerId)
                ->where('is_correct', true)
                ->exists();

            $newMark = 0;

            foreach ($answers as $qID => $aID) {
                $q = Question::with('answers')->find($qID);
                if ($q && $q->answers->where('id', $aID)->where('is_correct', true)->count()) {
                    $newMark++;
                }
            }

            if ($attempt->marks !== $newMark) {
                $attempt->marks = $newMark;
                $attempt->save();
            }
        }

        return redirect()->route('questions.index')->with('message', 'تم تحديث السؤال بنجاح وتم تعديل درجات الطلاب المتأثرين.');
    }



    public function delete($id)
    {
        $question = Question::find($id);
        $question->delete();
        return redirect()->route('questions.index')->with('message', 'تم حذف السؤال بنجاح!');
    }

    public function destroyAll()
    {
        foreach (Question::all() as $question) {
            $question->answers()->delete();
            $question->delete();
        }

        return redirect()->route('questions.index')->with('message', 'تم حذف بنك الأسئلة بالكامل مع كل الإجابات!');
    }
}
