<?php

namespace App\Http\Resources\API;

use Illuminate\Http\Request;
use App\Http\Resources\QuestionResource;
use Illuminate\Http\Resources\Json\JsonResource;

class ExamsResource extends JsonResource
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
            'course' => $this->course->name,
            'name' => $this->name,
            'duration' => $this->duration,
            'from_date' => $this->from_date,
            'instructions' => $this->instructions,
        ];
    }
}
