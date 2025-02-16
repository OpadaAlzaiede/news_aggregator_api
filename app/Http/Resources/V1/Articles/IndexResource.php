<?php

namespace App\Http\Resources\V1\Articles;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class IndexResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'slug' => $this->slug,
            'title' => $this->title,
            'description' => $this->description,
            'author' => $this->author,
            'category' => $this->category,
            'source' => $this->source,
            'published_at' => $this->published_at,
        ];
    }
}
