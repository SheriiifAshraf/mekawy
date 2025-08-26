<?php

namespace App\Http\Controllers\API;

use App\Models\Video;
use App\Models\Course;
use App\Models\Lesson;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Schema;
use App\Http\Resources\API\VideoResource;
use App\Http\Resources\API\LessonResource;

class LessonController extends Controller
{
    public function lessons($id)
    {
        $course = Course::find($id);

        if ($course) {
            $lessons = $course->lessons;

            return response()->json([
                'success' => true,
                'data' => LessonResource::collection($lessons)
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Course not found'
            ], 404);
        }
    }

    public function getVideos($lessonId, Request $request)
    {
        $student = $request->user();
        if (!$student) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 401);
        }

        $lesson = Lesson::with([
            'videos' => function ($q) {
                $q->where('publish_at', '<=', now())
                    ->when(
                        Schema::hasColumn('videos', 'position'),
                        fn($qq) => $qq->orderBy('position'),
                        fn($qq) => $qq->orderBy('id')
                    );
            },
            'course'
        ])->find($lessonId);

        if (!$lesson) {
            return response()->json(['message' => 'Lesson not found.'], 404);
        }

        $isFreeCourse = (bool) ($lesson->course->free ?? false);
        $isSubscribed = $isFreeCourse ? true : $student->subscriptions()
            ->where('course_id', $lesson->course_id)
            ->where('status', 1)
            ->exists();

        if (!$isFreeCourse && $isSubscribed) {
            $previousLesson = Lesson::where('id', '<', $lesson->id)
                ->where('course_id', $lesson->course_id)
                ->orderBy('id', 'desc')
                ->first();

            if ($previousLesson) {
                $existsUncompleted = DB::table('videos')
                    ->where('lesson_id', $previousLesson->id)
                    ->whereNotIn('id', function ($sub) use ($student) {
                        $sub->from('videos_viewers')
                            ->select('video_id')
                            ->where('student_id', $student->id)
                            ->where('completed', 1);
                    })
                    ->exists();

                if ($existsUncompleted) {
                    return response()->json([
                        'success' => false,
                        'message' => 'You must watch all videos of previous lessons before accessing this one.'
                    ], 403);
                }
            }
        }

        $completedIds = DB::table('videos_viewers')
            ->where('student_id', $student->id)
            ->where('completed', 1)
            ->pluck('video_id')
            ->toArray();

        $prevCompleted = true;
        $prepared = $lesson->videos->map(function ($v) use (&$prevCompleted, $isSubscribed, $completedIds) {
            $lockedSubscription = !$isSubscribed;
            $lockedSequence     = !$prevCompleted;
            $v->locked = $lockedSubscription || $lockedSequence;
            $v->lock_reason = $lockedSubscription ? 'subscription' : ($lockedSequence ? 'sequence' : null);

            $v->total_views = (int) DB::table('videos_viewers')
                ->where('video_id', $v->id)
                ->sum('view_count');

            $prevCompleted = in_array($v->id, $completedIds);

            return $v;
        });

        return response()->json([
            'success'     => true,
            'subscribed'  => $isSubscribed,
            'data'        => VideoResource::collection($prepared),
        ]);
    }

    public function progress(Request $request, Video $video)
    {
        $student = $request->user();
        if (!$student) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 401);
        }

        $lesson = $video->lesson()->with(['videos' => function ($q) {
            $q->when(
                Schema::hasColumn('videos', 'position'),
                fn($qq) => $qq->orderBy('position')->orderBy('id'),
                fn($qq) => $qq->orderBy('id')
            );
        }, 'course'])->firstOrFail();

        $isSubscribed = $lesson->course->free ? true : $student->subscriptions()
            ->where('course_id', $lesson->course_id)
            ->where('status', 1)
            ->exists();

        if (!$isSubscribed) {
            return response()->json([
                'success' => false,
                'message' => 'You must subscribe to access this video.'
            ], 403);
        }

        $videos = $lesson->videos->values();
        $idx = $videos->search(fn($v) => $v->id === $video->id);

        if ($idx > 0) {
            $prevId = $videos[$idx - 1]->id;
            $prevCompleted = DB::table('videos_viewers')
                ->where('student_id', $student->id)
                ->where('video_id', $prevId)
                ->where('completed', 1)
                ->exists();

            if (!$prevCompleted) {
                return response()->json([
                    'success' => false,
                    'message' => 'You must watch the previous video first.'
                ], 403);
            }
        }

        DB::table('videos_viewers')->updateOrInsert(
            ['student_id' => $student->id, 'video_id' => $video->id],
            [
                'view_count'   => DB::raw('COALESCE(view_count,0) + 1'),
                'completed'    => 1,
                'completed_at' => now(),
                'updated_at'   => now(),
            ]
        );

        return response()->json(['success' => true]);
    }
}
