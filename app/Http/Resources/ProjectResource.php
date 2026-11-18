<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProjectResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'title' => $this->title,

            'slug' => $this->slug,

            'summary' => $this->short_description,

            'thumbnail' => $this->thumbnail
                ? asset('storage/'.$this->thumbnail)
                : null,

            'category' => $this->category?->name,

            'technologies' => $this->technologies->pluck('name'),
        ];
    }
}