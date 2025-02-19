<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Builder;

trait Publishable
{
    public function scopeUnpublished(Builder $query): Builder
    {
        return $query
            ->whereNull('published_at')
            ->orderByDesc('id');
    }

    public function scopePublished(Builder $query): Builder
    {
        return $query
            ->whereNotNull('published_at')
            ->orderByDesc('id');
    }
}
