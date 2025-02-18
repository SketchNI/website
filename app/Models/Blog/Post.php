<?php

namespace App\Models\Blog;

use App\Models\Comment;
use App\Models\User;
use App\Traits\Commentable;
use Database\Factories\Blog\PostFactory;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;
use Qirolab\Laravel\Reactions\Contracts\ReactableInterface;
use Qirolab\Laravel\Reactions\Models\Reaction;
use Qirolab\Laravel\Reactions\Traits\Reactable;
use Venturecraft\Revisionable\Revision;
use Venturecraft\Revisionable\RevisionableTrait;

/**
 *
 *
 * @property int $id
 * @property int $user_id
 * @property string $title
 * @property string $slug
 * @property string $excerpt
 * @property string $content
 * @property string|null $featured_image
 * @property Carbon|null $published_at
 * @property Carbon|null $deleted_at
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Collection<int, Category> $categories
 * @property-read Model|Eloquent $commentable
 * @property-read Collection<int, Comment> $comments
 * @property-read bool $is_reacted
 * @property-read Collection|static[] $reaction_summary
 * @property-read \Illuminate\Support\Collection $reactions_by
 * @property-read Collection<int, Reaction> $reactions
 * @property-read Collection<int, Revision> $revisionHistory
 * @property-read User|null $user
 * @method static PostFactory factory($count = null, $state = [])
 * @method static Builder<static>|Post isDeleted()
 * @method static Builder<static>|Post isNotDeleted()
 * @method static Builder<static>|Post newModelQuery()
 * @method static Builder<static>|Post newQuery()
 * @method static Builder<static>|Post onlyTrashed()
 * @method static Builder<static>|Post published()
 * @method static Builder<static>|Post query()
 * @method static Builder<static>|Post unpublished()
 * @method static Builder<static>|Post whereContent($value)
 * @method static Builder<static>|Post whereCreatedAt($value)
 * @method static Builder<static>|Post whereDeletedAt($value)
 * @method static Builder<static>|Post whereExcerpt($value)
 * @method static Builder<static>|Post whereFeaturedImage($value)
 * @method static Builder<static>|Post whereId($value)
 * @method static Builder<static>|Post wherePublishedAt($value)
 * @method static Builder<static>|Post whereReactedBy($userId = null, $type = null)
 * @method static Builder<static>|Post whereSlug($value)
 * @method static Builder<static>|Post whereTitle($value)
 * @method static Builder<static>|Post whereUpdatedAt($value)
 * @method static Builder<static>|Post whereUserId($value)
 * @method static Builder<static>|Post withTrashed()
 * @method static Builder<static>|Post withoutTrashed()
 *
 * @mixin Eloquent
 */
class Post extends Model implements ReactableInterface
{
    use Commentable;

    /** @use HasFactory<PostFactory> */
    use HasFactory;

    use Reactable;
    use RevisionableTrait;
    use SoftDeletes;

    protected $table = 'blog_posts';

    protected $with = [
        'reactions',
    ];

    public function comments(): MorphMany
    {
        return $this->morphMany(Comment::class, 'commentable');
    }

    protected $fillable = [
        'title',
        'slug',
        'excerpt',
        'content',
        'featured_image',
        'published_at',
    ];

    protected $appends = [
        'reaction_summary',
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
            ->withTrashed()
            ->orderByDesc('id');
    }

    public function scopeIsNotDeleted(Builder $query): Builder
    {
        return $query
            ->with(['user', 'categories'])
            ->withoutTrashed()
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

    public function setFeaturedImage(?string $image): Post
    {
        $this->featured_image = $image;

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
