<?php

namespace App\Models\Blog;

use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;
use Venturecraft\Revisionable\RevisionableTrait;

class Post extends Model
{
    use HasFactory;
    use SoftDeletes;
    use RevisionableTrait;

    protected $table = 'blog_posts';

    protected $fillable = [
        'title',
        'slug',
        'excerpt',
        'content',
        'published_at',
    ];

    protected function casts(): array
    {
        return [
            'published_at' => 'datetime',
        ];
    }

    public function counts(): array
    {
        return [
            'posts' => $this->count(),
            'deleted' => $this->isDeleted()->count(),
            'unpublished' => $this->unpublished()->count(),
        ];
    }

    public function scopeUnpublished(Builder $query): Builder
    {
        return $query
            ->with(['user', 'categories'])
            ->whereNull('published_at')
            ->orderByDesc('id');
    }

    public function scopePublished(Builder $query): Builder
    {
        return $query
            ->with(['user', 'categories'])
            ->whereNotNull('published_at')
            ->orderByDesc('id');
    }

    public function scopeIsDeleted(Builder $query): Builder
    {
        return $query
            ->with(['user', 'categories'])
            ->whereNotNull('deleted_at')
            ->orderByDesc('id');
    }

    public function scopeIsNotDeleted(Builder $query): Builder
    {
        return $query
            ->with(['user', 'categories'])
            ->whereNull('deleted_at')
            ->orderByDesc('id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function categories(): HasManyThrough
    {
        return $this->hasManyThrough(Category::class, PostHasCategory::class, 'post_id', 'id', 'id', 'cat_id');
    }

    public function setAuthor(int $id): Post
    {
        $this->user_id = $id;

        return $this;
    }

    public function setTitle(string $title): Post
    {
        $this->title = $title;
        $this->setSlug($title);

        return $this;
    }

    public function setSlug(string $title): void
    {
        $this->slug = Str::slug($title);
    }

    public function setExcerpt(string $excerpt): Post
    {
        $this->excerpt = $excerpt;

        return $this;
    }

    public function setContent(?string $content): Post
    {
        $this->content = $content;

        return $this;
    }

    public function setPublishedAt(bool $is_published): Post
    {
        if ($is_published) {
            // $this->forbidden('publish blog entry', $this);
            $this->published_at = now();
        } else {
            $this->published_at = null;
        }

        return $this;
    }
}
