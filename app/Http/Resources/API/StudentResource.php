<?php

namespace App\Http\Resources\API;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class StudentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return
            [
                'id' => $this->id,
                'first_name' => $this->first_name,
                'last_name' => $this->last_name,
                'phone' => $this->phone,
                'father_phone' => $this->father_phone,
                'token' => $this->token,
                'location' => [
                    'id' => $this->location->id,
                    'name' => $this->location->name,
                ],
                'email' => $this->email
            ];
    }

    public function __construct($resource, $token)
    {
        parent::__construct($resource);
        $this->token = $token;
    }
}
