<?php

namespace App\Http\Resources\Blog;

use App\Http\Resources\Backend\CategoryChildResource;
use App\Models\Blog\Category;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin Category */
class CategoryResource extends JsonResource
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
            'posts_count' => $this->load('posts')->posts->whereNull('deleted_at')->whereNotNull('published_at')->count(),
            'slug' => $this->slug,
            'name' => $this->name,
        ];
    }
}
