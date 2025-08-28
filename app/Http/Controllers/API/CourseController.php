<?php

namespace App\Http\Controllers\API;

use App\Models\Course;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\API\CourseResource;

class CourseController extends Controller
{
    public function courses()
    {
        $student = Auth::guard('student')->user();

        $courses = Course::with('lessons.videos')->get();

        $courses->transform(function ($course) use ($student) {
            if ($course->free) {
                $course->subscription_status = 'مجاني';
            } else {
                $isSubscribed = $student->subscriptions()
                    ->where('course_id', $course->id)
                    ->where('status', 1)
                    ->exists();

                $course->subscription_status = $isSubscribed ? 'مشترك بالفعل' : 'إشترك الآن';
            }

            return $course;
        });

        $courses = CourseResource::collection($courses);

        return response()->json([
            'success' => true,
            'data' => $courses,
        ]);
    }

    public function latestCourses()
    {
        $student = Auth::guard('student')->user();

        $courses = Course::with('lessons.videos')
            ->latest()
            ->take(3)
            ->get();

        $courses->transform(function ($course) use ($student) {
            if ($course->free) {
                $course->subscription_status = 'مجاني';
            } else {
                if ($student) {
                    $isSubscribed = $student->subscriptions()
                        ->where('course_id', $course->id)
                        ->where('status', 1)
                        ->exists();

                    $course->subscription_status = $isSubscribed ? 'مشترك بالفعل' : 'إشترك الآن';
                } else {
                    $course->subscription_status = 'سجل الدخول لمشاهدة الكورس';
                }
            }

            return $course;
        });

        $courses = CourseResource::collection($courses);

        return response()->json([
            'success' => true,
            'data' => $courses,
        ]);
    }
}
