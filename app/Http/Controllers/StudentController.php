<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\Location;
use Illuminate\Http\Request;
use App\Models\EducationStage;
use Illuminate\Support\Facades\Hash;

class StudentController extends Controller
{
    public function index()
    {
        $students = Student::with(['courses', 'homeworkAttempts', 'examAttempts'])->get();
        return view('back.pages.students.index', compact('students'));
    }

    public function create()
    {
        $locations = Location::all();
        $stages = EducationStage::all();
        return view('back.pages.students.create', compact('locations', 'stages'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'first_name'         => 'required|string|max:255',
            'middle_name'        => 'required|string|max:255',
            'last_name'          => 'required|string|max:255',
            'phone'              => 'required|unique:students,phone',
            'father_phone'       => 'required|string|max:255',
            'location_id'        => 'required|exists:locations,id',
            'education_stage_id' => 'required|exists:education_stages,id',
            'grade_id'           => 'required|exists:grades,id',
            'password'           => 'required|string|min:8|confirmed',
        ]);

        Student::create([
            'first_name'         => $request->first_name,
            'middle_name'        => $request->middle_name,
            'last_name'          => $request->last_name,
            'phone'              => $request->phone,
            'father_phone'       => $request->father_phone,
            'location_id'        => $request->location_id,
            'education_stage_id' => $request->education_stage_id,
            'grade_id'           => $request->grade_id,
            'password'           => $request->password,
        ]);

        return redirect()->route('students.index')->with('message', 'تم إضافة الطالب بنجاح!');
    }


    public function destroy($id)
    {
        $student = Student::findOrFail($id);
        $student->delete();

        return redirect()->route('students.index')->with('message', 'تم حذف الطالب بنجاح!');
    }

    public function changePassword(Request $request, $id)
    {
        $student = Student::findOrFail($id);

        $request->validate([
            'password' => 'required|string|min:8|confirmed',
        ]);

        $student->password = $request->password;
        $student->save();

        return redirect()->route('students.index')->with('message', 'تم تغيير كلمة المرور بنجاح!');
    }

    public function destroyAll()
    {
        Student::query()->delete();
        return redirect()->route('students.index')->with('message', 'تم حذف جميع الطلاب بنجاح!');
    }
}
