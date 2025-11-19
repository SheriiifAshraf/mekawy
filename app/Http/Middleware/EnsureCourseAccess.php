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
        // لازم يكون طالب مسجّل دخول (مع إن فيه auth:student في الجروب برضه)
        $student = auth('student')->user();
        if (!$student) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized',
            ], 401);
        }

        // نجيب البارامترات من الراوت
        $route   = $request->route();
        $params  = $route?->parameters() ?? [];
        $courseId = null;

        /**
         * 1) لو الراوت فيه course أو course_id
         *    ممكن ييجي رقم أو Model نتيجة Route Model Binding
         */
        if (isset($params['course']) || isset($params['course_id'])) {
            $routeCourse = $params['course'] ?? $params['course_id'];

            if ($routeCourse instanceof Course) {
                $courseId = (int) $routeCourse->getKey();
            } else {
                $courseId = (int) $routeCourse;
            }

            /**
             * 2) لو الراوت فيه lesson (في مسار videos/{lesson} مثلاً)
             *    برضه ممكن يكون ID أو Lesson Model
             */
        } elseif (isset($params['lesson'])) {
            $routeLesson = $params['lesson'];

            if ($routeLesson instanceof Lesson) {
                $lesson = $routeLesson;
            } else {
                $lessonId = (int) $routeLesson;
                $lesson   = Lesson::select('id', 'course_id')->find($lessonId);
            }

            if (!$lesson) {
                return response()->json([
                    'success' => false,
                    'message' => 'Lesson not found',
                ], 404);
            }

            $courseId = (int) $lesson->course_id;

            /**
             * 3) لو الراوت فيه video (في مسار videos/{video}/progress)
             *    برضه ممكن يكون ID أو Video Model
             */
        } elseif (isset($params['video'])) {
            $routeVideo = $params['video'];

            if ($routeVideo instanceof Video) {
                $video = $routeVideo;
            } else {
                $videoId = (int) $routeVideo;
                $video   = Video::select('id', 'lesson_id')->find($videoId);
            }

            if (!$video) {
                return response()->json([
                    'success' => false,
                    'message' => 'Video not found',
                ], 404);
            }

            // نجيب الكورس عن طريق الدرس
            $courseId = (int) Lesson::where('id', $video->lesson_id)->value('course_id');

            if (!$courseId) {
                return response()->json([
                    'success' => false,
                    'message' => 'Cannot determine course',
                ], 400);
            }
        } else {
            // مفيش أي حاجة تخلينا نحدد الكورس
            return response()->json([
                'success' => false,
                'message' => 'Invalid course',
            ], 400);
        }

        // 4) لو الكورس مجاني → عدّي على طول
        $isFree = (bool) Course::where('id', $courseId)->value('free');
        if ($isFree) {
            return $next($request);
        }

        // 5) هل عنده اشتراك فعّال في جدول الاشتراكات؟
        $hasActiveSubscription = $student->subscriptions()
            ->where('course_id', $courseId)
            ->where('status', 1)
            ->exists();

        // 6) هل عنده كود فعّال للكورس ده؟
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

        // 7) لو لا اشتراك ولا كود → امنعه برسالة 403
        if (!$hasActiveSubscription && !$hasActiveCode) {
            return response()->json([
                'success' => false,
                'message' => 'انتهت صلاحية وصولك إلى هذه الدورة أو لم تعد متاحة',
            ], 403);
        }

        // كل شيء تمام → كمّل الطلب
        return $next($request);
    }
}
