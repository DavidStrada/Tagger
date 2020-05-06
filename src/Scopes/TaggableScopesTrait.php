<?php

namespace DavidStrada\Tagger\Scopes;

use Illuminate\Database\Eloquent\Builder;

trait TaggableScopesTrait
{
    /**
     * Get Model with Any Tag Provided.
     * @param  Builder $query
     * @param  array   $tags
     * @return \Illuminate\Database\Eloquent\Builder|static
     */
    public function scopeWithAnyTag(Builder $query, array $tags) : Builder
    {
        return $query->hasTags($tags);
    }

    /**
     * Get Model with All Tags Provided.
     * @param  Builder $query
     * @param  array   $tags
     * @return \Illuminate\Database\Eloquent\Builder|static
     */
    public function scopeWithAllTags(Builder $query, array $tags) : Builder
    {
        collect($tags)->each(fn($tag) => $query->hasTags([$tag]));

        return $query;
    }

    /**
     * Check if Model has tags
     * @param  Builder $query
     * @param  array   $tags
     * @return \Illuminate\Database\Eloquent\Builder|static
     */
    private function scopeHasTags(Builder $query, array $tags) : Builder
    {
        return $query->whereHas('tags', fn($query) => $query->whereIn('slug', $tags));
    }
}
