<?php

namespace App\Http\Resources\API;

use Illuminate\Http\Request;
use App\Http\Resources\MediaResource;
use Illuminate\Http\Resources\Json\JsonResource;

class VideoResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'lesson' => $this->lesson->name,
            'id' => $this->id,
            'name' => $this->name,
            'link' => $this->link,
            'pdf' => new MediaResource($this->getFirstMedia('pdfs')),
            'image' => new MediaResource($this->getFirstMedia('images')),
        ];
    }
}
