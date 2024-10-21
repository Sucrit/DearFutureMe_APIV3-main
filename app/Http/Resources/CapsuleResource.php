<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CapsuleResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        // return parent::toArray($request);
        return [
            'id' => $this->id,
            'title' => $this->title, 
            'message' => $this->message, 
            'content' => $this->content,
            'receiver_email' => $this->receiver_email,
            'schedule_open_at' => $this->schedule_open_at
        ];
    }
}
