<?php

namespace App\Http\Resources\Blog;

use App\Http\Resources\CommentResource;
use App\Http\Resources\TagResource;
use App\Http\Resources\UserResource;
use App\Models\Post;
use App\Services\Markdown;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use League\CommonMark\Exception\CommonMarkException;

/** @mixin Post */
class PostResource extends JsonResource
{
    /**
     * @throws CommonMarkException
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'author' => $this->whenLoaded('user', new UserResource($this->user)->resolve()),
            'title' => $this->title,
            'slug' => $this->slug,
            'url' => route('blog.show', ['post' => $this->id]),
            'excerpt' => $this->excerpt,
            'raw_content' => $this->content,
            'content' => $this->content === null ? null : Markdown::make($this->content),
            'featured_image' => null,
            'tags' => TagResource::collection($this->tags)->resolve(),
            'comments' => CommentResource::collection($this->load('comments')->comments)->resolve(),
            'reactions' => ReactionResource::collection($this->reactions()->get())->resolve(),
            'reactions_summary' => $this->reaction_summary,
            'published_at' => $this->published_at?->toIso8601String(),
            'deleted_at' => $this->deleted_at?->toIso8601String(),
            'created_at' => $this->created_at->toIso8601String(),
            'updated_at' => $this->updated_at->toIso8601String(),
            'raw_updated_at' => $this->updated_at->format('r'),
        ];
    }
}
