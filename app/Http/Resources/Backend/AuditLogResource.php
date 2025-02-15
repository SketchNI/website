<?php

namespace App\Http\Resources\Backend;

use App\Http\Resources\Backend;
use App\Http\Resources\Blog\PostResource;
use App\Models\Blog\Post;
use App\Models\Team;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Spatie\Activitylog\Models\Activity;

/** @mixin Activity */
class AuditLogResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'type' => $this->log_name,
            'target' => $this->convertToLink($this->subject_type, $this->subject_id),
            'description' => $this->description,
            'subject' => match ($this->subject_type) {
                User::class => new Backend\UserResource($this->subject),
                Post::class => new PostResource($this->subject),
                default => null,
            },
            'subject_data' => [
                'subject_type' => $this->subject_type,
                'subject_id' => $this->subject_id,
            ],
            'user' => new Backend\UserResource($this->causer),
            'event' => $this->event,
            'properties' => $this->properties,
            'created_at' => $this->created_at,
        ];
    }

    private function convertToLink(string $model, int $id): array
    {
        return match ($model) {
            User::class => [
                'url' => route('backend.users.show', $user = User::find($id)),
                'label' => '[User] '.$user->name,
            ],
            Post::class => [
                'url' => route('backend.blog.edit', $post = Post::withTrashed()->find($id)),
                'label' => '[Post] '.$post->title,
            ],
            Team::class => [
                'url' => route('backend.teams.edit', $team = Team::find($id)),
                'label' => '[Team] '.$team->name,
            ],
            default => [],
        };
    }
}
