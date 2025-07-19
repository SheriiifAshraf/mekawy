<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Student;
use App\Models\Subscription;
use Illuminate\Http\Request;

class SubscriptionController extends Controller
{
    public function index()
    {
        $subscriptions = Subscription::with('student', 'course')->get();
        return view('back.pages.subscriptions.index', compact('subscriptions'));
    }

    public function create()
    {
        $students = Student::all();
        $courses = Course::with('lessons.videos')->get();

        return view('back.pages.subscriptions.create', compact('students', 'courses'));
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'subscriptions' => 'required|array',
            'subscriptions.*.student_id' => 'required|exists:students,id',
            'subscriptions.*.course_ids' => 'required|array|min:1',
            'subscriptions.*.course_ids.*' => 'exists:courses,id',
        ], [
            'subscriptions.required' => 'يجب إضافة اشتراك واحد على الأقل.',
            'subscriptions.*.student_id.required' => 'الرجاء اختيار الطالب.',
            'subscriptions.*.student_id.exists' => 'الطالب المختار غير موجود.',
            'subscriptions.*.course_ids.required' => 'الرجاء اختيار كورس واحد على الأقل.',
            'subscriptions.*.course_ids.*.exists' => 'أحد الكورسات المختارة غير موجود.',
        ]);

        foreach ($validatedData['subscriptions'] as $sub) {
            foreach ($sub['course_ids'] as $courseId) {
                $exists = Subscription::where('student_id', $sub['student_id'])
                    ->where('course_id', $courseId)
                    ->exists();

                if ($exists) {
                    return redirect()->back()
                        ->withErrors(['custom_error' => 'الطالب مشترك بالفعل في أحد الكورسات المختارة.'])
                        ->withInput();
                }

                Subscription::create([
                    'student_id' => $sub['student_id'],
                    'course_id' => $courseId,
                    'status' => 1
                ]);
            }
        }

        return redirect()->route('subscriptions.index')->with('message', 'تم إضافة الاشتراكات بنجاح');
    }




    public function updateStatus(Request $request)
    {
        $subscription = Subscription::find($request->id);

        if ($subscription) {
            $subscription->status = $subscription->status == 1 ? 0 : 1;
            $subscription->save();

            return response()->json(['success' => true, 'status' => $subscription->status]);
        }

        return response()->json(['success' => false]);
    }

    public function destroy($id)
    {
        $subscription = Subscription::find($id);

        if ($subscription) {
            $subscription->delete();

            return redirect()->route('subscriptions.index')->with('message', 'تم حذف الاشتراك بنجاح');
        }

        return redirect()->route('subscriptions.index')->with('error', 'الاشتراك غير موجود');
    }

    public function destroyAll()
    {
        Subscription::query()->delete();
        return redirect()->route('subscriptions.index')->with('message', 'تم حذف جميع الاشتراكات بنجاح!');
    }
}
