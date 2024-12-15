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
            'parent' => $this->parent_id !== null ? [
                'id' => $this->parent_id,
                'category' => $this->parent->name,
            ] : null,
            'posts_count' => $this->posts_count,
            'slug' => $this->slug,
            'name' => $this->name,
        ];
    }
}
