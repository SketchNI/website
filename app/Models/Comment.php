<?php

namespace App\Models;

use App\Models\Blog\Post;
use Database\Factories\CommentsFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Venturecraft\Revisionable\RevisionableTrait;

class Comment extends Model
{
    /** @use HasFactory<CommentsFactory> */
    use HasFactory;

    use RevisionableTrait;

    public function post(): BelongsTo
    {
        return $this->belongsTo(Post::class);
    }

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function setAuthor(int $id): Comment
    {
        $this->user_id = $id;

        return $this;
    }

    public function setMorphs(int $id, string $type): Comment
    {
        $this->commentable_id = $id;
        $this->commentable_type = $type;

        return $this;
    }

    public function setComment(string $comment): Comment
    {
        $this->comment = $comment;
        return $this;
    }
}
