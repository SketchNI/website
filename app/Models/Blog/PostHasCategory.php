<?php

namespace App\Models\Blog;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Venturecraft\Revisionable\Revision;
use Venturecraft\Revisionable\RevisionableTrait;

/**
 *
 *
 * @property int $id
 * @property int $post_id
 * @property int $cat_id
 * @property-read Category|null $category
 * @property-read Post $post
 * @property-read Collection<int, Revision> $revisionHistory
 * @method static Builder<static>|PostHasCategory newModelQuery()
 * @method static Builder<static>|PostHasCategory newQuery()
 * @method static Builder<static>|PostHasCategory query()
 * @method static Builder<static>|PostHasCategory whereCatId($value)
 * @method static Builder<static>|PostHasCategory whereId($value)
 * @method static Builder<static>|PostHasCategory wherePostId($value)
 *
 * @mixin Eloquent
 */
class PostHasCategory extends Model
{
    use RevisionableTrait;

    public $timestamps = false;

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function post(): BelongsTo
    {
        return $this->belongsTo(Post::class);
    }
}
