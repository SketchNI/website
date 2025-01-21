<?php

namespace App\Http\Resources;

use App\Models\Blog\Category;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin Category */ class CategoryResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'parent_id' => $this->parent_id,
            'parent' => $this->whenLoaded('parent'),
            'children' => $this->whenLoaded(
                'children',
                CategoryChildResource::collection($this->load('children')->children)->resolve()
            ),
            'posts_count' => $this->posts_count ?? 0,
            'slug' => $this->slug,
            'name' => $this->name,
        ];
    }
}
