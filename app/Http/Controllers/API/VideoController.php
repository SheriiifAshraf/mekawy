<?php

namespace App\Http\Controllers\API;

use App\Models\Video;
use App\Models\Lesson;
use App\Events\VideoViewed;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\API\VideoResource;

class VideoController extends Controller
{
    public function showAndRecordViews($lessonId, $id, Request $request)
    {
        $lesson = Lesson::find($lessonId);
        if (!$lesson) {
            return response()->json([
                'success' => false,
                'message' => 'Lesson not found'
            ], 404);
        }

        $student = $request->user();
        if (!$student) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized'
            ], 401);
        }

        $now = now();
        $videos = $lesson->videos()->where('publish_at', '<=', $now)->get();

        if ($videos->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'No videos found for this lesson'
            ], 404);
        }

        $video = $videos->where('id', $id)->first();
        if (!$video) {
            return response()->json([
                'success' => false,
                'message' => 'Video not found in the lesson'
            ], 404);
        }

        $unviewedVideos = $videos
            ->where('id', '<', $id)
            ->whereNotIn('id', $student->videoViews()->pluck('video_id'))
            ->count();

        if ($unviewedVideos > 0) {
            return response()->json([
                'success' => false,
                'message' => 'You must watch all previous videos before accessing this one.'
            ], 403);
        }

        VideoViewed::dispatch($id, $student->id);

        return response()->json([
            'success' => true,
            'message' => 'Videos fetched and view recorded',
            'data' => [
                'videos' => VideoResource::collection($videos),
                'viewed_video' => new VideoResource($video)
            ]
        ]);
    }
}
