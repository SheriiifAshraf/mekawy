<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MediaResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $data = [
            'id' => $this->id,
            'file_name' => $this->file_name,
            'original_url' => $this->original_url,
            'extension' => $this->extension,
            'size' => $this->size
        ];
        return $data;
    }
}
