<?php

namespace App\Http\Controllers\API;

use App\Models\Course;
use App\Models\Lesson;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
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
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized'
            ], 401);
        }

        $lesson = Lesson::with(['videos' => function ($query) {
            $query->where('publish_at', '<=', now());
        }, 'course'])->find($lessonId);

        if (!$lesson) {
            return response()->json(['message' => 'Lesson not found.'], 404);
        }

        $isFreeCourse = $lesson->course->free;

        if (!$isFreeCourse) {
            $isSubscribed = $student->subscriptions()
                ->where('course_id', $lesson->course->id)
                ->where('status', 1)
                ->exists();

            if (!$isSubscribed) {
                return response()->json([
                    'success' => false,
                    'message' => 'You are not subscribed to this course.'
                ], 403);
            }

            $previousLesson = Lesson::where('id', '<', $lessonId)
                ->where('course_id', $lesson->course->id)
                ->orderBy('id', 'desc')
                ->first();

            if ($previousLesson) {
                $unwatchedVideos = $previousLesson->videos()
                    ->whereNotIn('id', $student->videoViews()->pluck('video_id'))
                    ->exists();

                if ($unwatchedVideos) {
                    return response()->json([
                        'success' => false,
                        'message' => 'You must watch all videos of previous lessons before accessing this one.'
                    ], 403);
                }
            }
        }

        foreach ($lesson->videos as $video) {
            $videoViewer = \DB::table('videos_viewers')
                ->where('video_id', $video->id)
                ->where('student_id', $student->id)
                ->first();

            if ($videoViewer) {
                \DB::table('videos_viewers')
                    ->where('video_id', $video->id)
                    ->where('student_id', $student->id)
                    ->increment('view_count');
            } else {
                \DB::table('videos_viewers')->insert([
                    'video_id' => $video->id,
                    'student_id' => $student->id,
                    'view_count' => 1,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            $video->total_views = \DB::table('videos_viewers')
                ->where('video_id', $video->id)
                ->sum('view_count');
        }

        return response()->json([
            'success' => true,
            'data' => VideoResource::collection($lesson->videos->map(function ($video) {
                $video->view_count = $video->total_views;
                return $video;
            })),
        ]);
    }
}
