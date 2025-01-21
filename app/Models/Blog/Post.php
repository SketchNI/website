<?php

namespace App\Models\Blog;

use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Tags\HasTags;

class Post extends Model
{
    use HasFactory;
    use HasTags;
    use SoftDeletes;

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

    public function scopeNormal(Builder $query): Builder
    {
        return $query
            ->with(['user', 'categories'])
            ->orderByDesc('id');
    }

    public function scopeUnpublished(Builder $query): Builder
    {
        return $query
            ->with(['user', 'categories'])
            ->whereNull('published_at')
            ->orderByDesc('id');
    }

    public function scopeIsDeleted(Builder $query): Builder
    {
        return $query
            ->with(['user', 'categories'])
            ->orderByDesc('id')
            ->onlyTrashed();
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function categories(): HasManyThrough
    {
        return $this->hasManyThrough(Category::class, PostHasCategory::class, 'post_id', 'id', 'id', 'cat_id');
    }
}
