<?php

namespace App\Models;

use Database\Factories\CommentFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Venturecraft\Revisionable\RevisionableTrait;

class Comment extends Model
{
    /** @use HasFactory<CommentFactory> */
    use HasFactory;

    use RevisionableTrait;

    public function post(): MorphTo
    {
        return $this->morphTo('commentable');
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
