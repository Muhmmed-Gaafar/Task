<?php

namespace App\Http\Resources;
use Illuminate\Http\Resources\Json\JsonResource;

class PostResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'body' => $this->body,
            'cover_image' => $this->cover_image,
            'pinned' => $this->pinned,
            'tags' => $this->tags->pluck('id'),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}

