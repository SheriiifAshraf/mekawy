<?php

namespace App\Http\Controllers;

use App\Models\Answer;
use App\Models\Question;
use App\Http\Requests\AnswerRequest;

use Illuminate\Http\Request;

class AnswerController extends Controller
{
    public function index(Question $question)
    {
        $answers = $question->answers;
        return view('back.pages.answers.index', compact('answers', 'question'));
    }

    public function create(Question $question)
    {
        return view('back.pages.answers.create', compact('question'));
    }

    public function store(AnswerRequest $request, Question $question)
    {
        $answer = $question->answers()->create($request->validated());

        if ($request->hasFile('image')) {
            $answer->addMedia($request->file('image'))->toMediaCollection('images');
        }

        return redirect()->route('answers.index', $question)->with('message', 'تم إضافة الإجابة بنجاح!');
    }

    public function show(Question $question, Answer $answer)
    {
        return view('answers.show', compact('answer'));
    }

    public function edit(Question $question, $id)
    {
        $answer = Answer::findOrFail($id);
        return view('back.pages.answers.edit', compact('answer', 'question'));
    }

    public function update(AnswerRequest $request, Question $question, $id)
    {
        $answer = Answer::findOrFail($id);
        $answer->update($request->validated());

        if ($request->hasFile('image')) {
            $answer->clearMediaCollection('images');
            $answer->addMedia($request->file('image'))->toMediaCollection('images');
        }

        return redirect()->route('answers.index', $question)->with('message', 'تم تعديل الإجابة بنجاح!');
    }

    public function delete(Question $question, $id)
    {
        $answer = Answer::find($id);
        $answer->delete();
        return redirect()->route('answers.index', $question)->with('message', 'تم حذف الإجابة بنجاح!');
    }
}
