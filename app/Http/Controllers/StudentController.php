<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\Location;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class StudentController extends Controller
{
    public function index()
    {
        // $students = Student::all();
        $students = Student::with(['courses', 'homeworkAttempts', 'examAttempts'])->get();
        return view('back.pages.students.index', compact('students'));
    }

    public function create()
    {
        $locations = Location::all();
        return view('back.pages.students.create', compact('locations'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'phone' => 'required|unique:students,phone',
            'email' => 'nullable|email|unique:students,email',
            'father_phone' => 'required',
            'location_id' => 'required|exists:locations,id',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $student = new Student();
        $student->first_name = $request->first_name;
        $student->last_name = $request->last_name;
        $student->phone = $request->phone;
        $student->email = $request->email;
        $student->father_phone = $request->father_phone;
        $student->location_id = $request->location_id;
        $student->password = $request->password;
        $student->save();

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
