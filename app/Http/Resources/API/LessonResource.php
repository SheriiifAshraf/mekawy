<?php

namespace App\Http\Resources\API;

use Illuminate\Http\Request;
use App\Http\Resources\MediaResource;
use Illuminate\Http\Resources\Json\JsonResource;

class LessonResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $totalCourseVideoDuration = $this->course->lessons->flatMap(function ($lesson) {
            return $lesson->videos;
        })->sum('duration');

        return [
            'course_name' => $this->course->name,
            'course_image' => $this->course->image,
            'course_price' => $this->course->price,
            'course_description' => $this->course->description,
            'course_subscription' => $this->course->free,

            'course_homework_count' => $this->course->homeworks->count(),
            'course_exam_count' => $this->course->exams->count(),
            'course_video_count' => $this->course->lessons->flatMap->videos->count(),

            'total_course_video_duration' => $totalCourseVideoDuration,

            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description,

            'lesson_duration_minutes' => $this->videos->sum('duration') * 60,

            'videos' => $this->videos->map(function ($video) {
                $viewCount = $video->viewers()->sum('view_count');

                return [
                    'id' => $video->id,
                    'title' => $video->name,
                    'duration_hours' => $video->duration,
                    'duration_minutes' => $video->duration * 60,
                    'number_of_views' => $viewCount,
                    'image' => $video->getFirstMedia('images')
                        ? new MediaResource($video->getFirstMedia('images'))
                        : null,
                ];
            }),
        ];
    }
}
