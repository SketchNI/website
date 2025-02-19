<?php

namespace App\Models;

use App\Traits\Publishable;
use Database\Factories\PageFactory;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;
use Venturecraft\Revisionable\Revision;
use Venturecraft\Revisionable\RevisionableTrait;

/**
 *
 *
 * @property int $id
 * @property int $user_id
 * @property string $title
 * @property string $slug
 * @property string $content
 * @property Carbon|null $published_at
 * @property Carbon|null $deleted_at
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Collection<int, Revision> $revisionHistory
 * @method static PageFactory factory($count = null, $state = [])
 * @method static Builder<static>|Page newModelQuery()
 * @method static Builder<static>|Page newQuery()
 * @method static Builder<static>|Page onlyTrashed()
 * @method static Builder<static>|Page query()
 * @method static Builder<static>|Page whereContent($value)
 * @method static Builder<static>|Page whereCreatedAt($value)
 * @method static Builder<static>|Page whereDeletedAt($value)
 * @method static Builder<static>|Page whereId($value)
 * @method static Builder<static>|Page wherePublishedAt($value)
 * @method static Builder<static>|Page whereSlug($value)
 * @method static Builder<static>|Page whereTitle($value)
 * @method static Builder<static>|Page whereUpdatedAt($value)
 * @method static Builder<static>|Page whereUserId($value)
 * @method static Builder<static>|Page withTrashed()
 * @method static Builder<static>|Page withoutTrashed()
 * @property-read User|null $user
 * @mixin Eloquent
 */
class Page extends Model
{
    use HasFactory;
    use RevisionableTrait;
    use SoftDeletes;
    use Publishable;

    protected $casts = [
        'published_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @param  int  $user_id
     *
     * @return Page
     */
    public function setUserId(int $user_id): Page
    {
        $this->user_id = $user_id;

        return $this;
    }

    /**
     * @param  string  $title
     * @param  bool  $is_slug
     *
     * @return Page
     */
    public function setTitle(string $title, bool $is_slug = true): Page
    {
        $this->title = $title;
        $is_slug && $this->setSlug($title);

        return $this;
    }

    public function setSlug(string $slug): Page
    {
        $this->slug = Str::slug($slug);

        return $this;
    }

    /**
     * @param  string  $content
     *
     * @return Page
     */
    public function setContent(string $content): Page
    {
        $this->content = $content;

        return $this;
    }

    /**
     * @param  Carbon|null  $published_at
     *
     * @return Page
     */
    public function setPublishedAt(?Carbon $published_at): Page
    {
        $this->published_at = $published_at;

        return $this;
    }
}
