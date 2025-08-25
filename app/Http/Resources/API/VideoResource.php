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
            'id' => $this->id,
            'title' => $this->name,
            'link' => $this->link,
            'duration_hours' => $this->duration,
            'duration_minutes' => $this->duration * 60,
            'number_of_views' => $this->view_count ?? 0,
            'image' => new MediaResource(optional($this->getFirstMedia('images'))),
            'pdf' => new MediaResource(optional($this->getFirstMedia('pdfs'))),
            'locked' => $this->locked ?? true,
        ];
    }
}
