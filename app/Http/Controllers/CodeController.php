<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateCodeRequest;
use App\Models\Code;
use App\Models\Course;
use App\Models\Lesson;
use App\Models\Student;
use App\Models\Video;
use Illuminate\Http\Request;

class CodeController extends Controller
{
    public function index()
    {
        $batches = Code::selectRaw('batch_id, created_at, period, COUNT(*) as count')
            ->groupBy('batch_id', 'created_at', 'period')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('back.pages.codes.index', compact('batches'));
    }


    public function batch($batchId)
    {
        $codes = Code::where('batch_id', $batchId)->get();
        return view('back.pages.codes.batch', compact('codes', 'batchId'));
    }

    public function deleteBatch($batch_id)
    {
        $deletedCodes = Code::where('batch_id', $batch_id)->delete();

        if ($deletedCodes > 0) {
            return redirect()->route('codes.index')->with('message', "تم حذف $deletedCodes كوداً بنجاح!");
        } else {
            return redirect()->route('codes.index')->with('message', 'لم يتم العثور على أكواد لهذا الـ batch!');
        }
    }


    public function create()
    {
        $students = Student::all();
        $courses = Course::all();
        $lessons = Lesson::all();
        $videos = Video::all();
        return view('back.pages.codes.create', compact('students', 'courses', 'lessons', 'videos'));
    }

    public function store(CreateCodeRequest $request)
    {
        $data = $request->validated();
        $codeCount = $request->input('code_count', 1);

        $batchId = uniqid();

        for ($i = 0; $i < $codeCount; $i++) {
            $data['code'] = uniqid();
            $data['batch_id'] = $batchId;
            Code::create($data);
        }

        return redirect()->route('codes.index')->with('message', 'تم إضافة الأكواد بنجاح!');
    }

    public function show($id)
    {
        // $code = Code::findOrFail($id);
        $code = Code::with('student')->findOrFail($id);
        return view('back.pages.codes.show', compact('code'));
    }

    public function edit($id)
    {
        $code = Code::findOrFail($id);
        $students = Student::all();
        return view('back.pages.codes.edit', compact('code', 'students'));
    }

    public function update(CreateCodeRequest $request, $id)
    {
        $code = Code::findOrFail($id);
        $data = $request->validated();
        $code->update($data);
        return redirect()->route('codes.index')->with('message', 'تم تعديل الكود بنجاح!');
    }

    public function destroy($id)
    {
        $code = Code::findOrFail($id);
        $code->delete();
        return redirect()->route('codes.index')->with('message', 'تم حذف الكود بنجاح!');
    }
}
