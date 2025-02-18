<?php

namespace App\Http\Resources;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Spatie\Tags\Tag;

/** @mixin Tag */
class TagResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'attached_count' => Post::withAllTags($this->name)->count(),
            'name' => $this->name,
            'slug' => $this->slug,
            'type' => $this->type,
        ];
    }
}
