<?php

namespace App\Models;

use App\Exceptions\ForbiddenException;
use App\Traits\Commentable;
use App\Traits\ThrowsException;
use Database\Factories\Blog\PostFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;
use Qirolab\Laravel\Reactions\Contracts\ReactableInterface;
use Qirolab\Laravel\Reactions\Traits\Reactable;
use Spatie\Tags\HasTags;
use Venturecraft\Revisionable\RevisionableTrait;

class Post extends Model implements ReactableInterface
{
    use Commentable;

    /** @use HasFactory<PostFactory> */
    use HasFactory;

    use HasTags;
    use Reactable;
    use RevisionableTrait;
    use SoftDeletes;
    use ThrowsException;

    protected $table = 'blog_posts';

    protected $with = [
        'tags',
        'comments',
        'reactions',
    ];

    protected $fillable = [
        'title',
        'slug',
        'excerpt',
        'content',
        'featured_image',
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
            'deleted' => $this->onlyTrashed()->count(),
            'unpublished' => $this->unpublished()->count(),
        ];
    }

    public function scopeUnpublished(Builder $query): Builder
    {
        return $query
            ->with(['user', 'tags'])
            ->whereNull('published_at')
            ->orderByDesc('id');
    }

    public function scopePublished(Builder $query): Builder
    {
        return $query
            ->with(['user', 'tags'])
            ->whereNotNull('published_at')
            ->orderByDesc('id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
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

    public function setFeaturedImage(?string $image): Post
    {
        $this->featured_image = $image;

        return $this;
    }

    /**
     * @throws ForbiddenException
     */
    public function setPublishedAt(bool $is_published): Post
    {
        if ($is_published) {
            $this->forbidden('publish blog entry');
            $this->published_at = now();
        } else {
            $this->published_at = null;
        }

        return $this;
    }
}
