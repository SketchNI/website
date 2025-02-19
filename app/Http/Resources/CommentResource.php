<?php

namespace App\Http\Resources;

use App\Models\Comment;
use App\Services\Markdown;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use League\CommonMark\Exception\CommonMarkException;

/** @mixin Comment */
class CommentResource extends JsonResource
{
    /**
     * @throws CommonMarkException
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'author' => new UserResource($this->load('author')->author)->resolve(),
            'comment' => Markdown::make($this->comment),
            'created_at' => $this->created_at,
        ];
    }
}
