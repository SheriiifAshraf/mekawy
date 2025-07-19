<?php

namespace App\Http\Resources\API;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CourseResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $totalDuration = $this->lessons->flatMap(function ($lesson) {
            return $lesson->videos;
        })->sum('duration');

        return [
            'id' => $this->id,
            'image' => $this->image,
            'name' => $this->name,
            'description' => $this->description,
            'price' => $this->price,
            'total_video_duration' => $totalDuration,
            'subscription_status' => $this->when(isset($this->subscription_status), $this->subscription_status),
            'homework_count' => $this->homeworks->count(),
            'exam_count' => $this->exams->count(),
            'video_count' => $this->lessons->flatMap->videos->count(),
        ];
    }
}
