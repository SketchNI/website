<?php

namespace App\Models;

use App\Exceptions\ForbiddenException;
use App\Traits\Commentable;
use App\Traits\Publishable;
use App\Traits\ThrowsException;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;
use Qirolab\Laravel\Reactions\Contracts\ReactableInterface;
use Qirolab\Laravel\Reactions\Models\Reaction;
use Qirolab\Laravel\Reactions\Traits\Reactable;
use Spatie\Tags\HasTags;
use Spatie\Tags\Tag;
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
 * @property-read Model|Eloquent $commentable
 * @property-read Collection<int, Comment> $comments
 * @property-read bool $is_reacted
 * @property-read Reaction $reacted
 * @property-read Collection|static[] $reaction_summary
 * @property-read \Illuminate\Support\Collection $reactions_by
 * @property-read Collection<int, Reaction> $reactions
 * @property-read Collection<int, Revision> $revisionHistory
 * @property Collection<int, Tag> $tags
 * @property-read User|null $user
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
 * @method static Builder<static>|Post withAllTags(\ArrayAccess|Tag|array|string $tags, ?string $type = null)
 * @method static Builder<static>|Post withAllTagsOfAnyType($tags)
 * @method static Builder<static>|Post withAnyTags(\ArrayAccess|Tag|array|string $tags, ?string $type = null)
 * @method static Builder<static>|Post withAnyTagsOfAnyType($tags)
 * @method static Builder<static>|Post withTrashed()
 * @method static Builder<static>|Post withoutTags(\ArrayAccess|Tag|array|string $tags, ?string $type = null)
 * @method static Builder<static>|Post withoutTrashed()
 * @mixin Eloquent
 */
class Post extends Model implements ReactableInterface
{
    use Commentable;
    use HasFactory;
    use HasTags;
    use Reactable;
    use RevisionableTrait;
    use SoftDeletes;
    use ThrowsException;
    use Publishable;

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
