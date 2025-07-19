<?php

namespace App\Http\Resources\API;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CodeResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'code' => $this->code,
            'student' => $this->student->first_name . ' ' . $this->student->last_name,
            'type' => $this->type,
            'status' => $this->status,
            'course' => $this->course->name ?? null,
            'lesson' => $this->lesson->name ?? null,
            'video' => $this->video->name ?? null,
        ];
    }
}
