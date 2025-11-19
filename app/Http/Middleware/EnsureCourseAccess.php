<?php

namespace App\Http\Middleware;

use App\Models\Code;
use App\Models\Course;
use App\Models\Lesson;
use App\Models\Video;
use Closure;
use Illuminate\Http\Request;

class EnsureCourseAccess
{
    public function handle(Request $request, Closure $next)
    {
        $student = auth('student')->user();
        if (!$student) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized',
            ], 401);
        }

        $params = $request->route()?->parameters() ?? [];
        $courseId = null;

        if (isset($params['course']) || isset($params['course_id'])) {
            $routeCourse = $params['course'] ?? $params['course_id'];
            $courseId = $routeCourse instanceof Course ? (int) $routeCourse->getKey() : (int) $routeCourse;
        } elseif (isset($params['lesson'])) {
            $lessonId = (int) $params['lesson'];
            $lesson = Lesson::select('id', 'course_id')->find($lessonId);
            if (!$lesson) {
                return response()->json([
                    'success' => false,
                    'message' => 'Lesson not found',
                ], 404);
            }
            $courseId = (int) $lesson->course_id;
        } elseif (isset($params['video'])) {
            $videoId = (int) $params['video'];
            $video = Video::select('id', 'lesson_id')->find($videoId);
            if (!$video) {
                return response()->json([
                    'success' => false,
                    'message' => 'Video not found',
                ], 404);
            }
            $courseId = (int) Lesson::where('id', $video->lesson_id)->value('course_id');
            if (!$courseId) {
                return response()->json([
                    'success' => false,
                    'message' => 'Cannot determine course',
                ], 400);
            }
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Invalid course',
            ], 400);
        }

        $isFree = (bool) Course::where('id', $courseId)->value('free');
        if ($isFree) {
            return $next($request);
        }

        $hasActiveSubscription = $student->subscriptions()
            ->where('course_id', $courseId)
            ->where('status', 1)
            ->exists();

        $hasActiveCode = Code::where('student_id', $student->id)
            ->where('course_id', $courseId)
            ->where('type', 'course')
            ->whereNull('canceled_at')
            ->whereNotIn('status', ['canceled', 'expired'])
            ->where(function ($q) {
                $q->whereNull('expires_at')
                    ->orWhere('expires_at', '>', now());
            })
            ->exists();

        if (!$hasActiveSubscription && !$hasActiveCode) {
            return response()->json([
                'success' => false,
                'message' => 'انتهت صلاحية وصولك إلى هذه الدورة أو لم تعد متاحة',
            ], 403);
        }

        return $next($request);
    }
}
