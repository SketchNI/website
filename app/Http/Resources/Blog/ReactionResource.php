<?php

namespace App\Http\Resources\Blog;

use App\Http\Resources\UserResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Qirolab\Laravel\Reactions\Models\Reaction;

/** @mixin Reaction */
class ReactionResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'user' => new UserResource($this->load('reactBy')->reactBy)->resolve(),
            'reaction' => $this->type,
        ];
    }
}
