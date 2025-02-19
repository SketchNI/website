<?php

namespace App\Http\Resources;

use App\Models\Page;
use App\Services\Markdown;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use League\CommonMark\Exception\CommonMarkException;

/** @mixin Page */
class PageResource extends JsonResource
{
    /**
     * @throws CommonMarkException
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'author' => new UserResource($this->load('user')->user)->resolve(),
            'title' => $this->title,
            'slug' => $this->slug,
            'url' => route('page.show', $this->slug),
            'raw_content' => $this->content,
            'stripped_content' => $this->content === null ? null : strip_tags(Markdown::make($this->content)),
            'content' => $this->content === null ? null : Markdown::make($this->content),
            'is_published' => !!$this->published_at,
            'published_at' => $this->published_at,
            'is_deleted' => !!$this->deleted_at,
            'deleted_at' => $this->deleted_at,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
