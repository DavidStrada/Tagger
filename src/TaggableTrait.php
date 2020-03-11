<?php

namespace DavidStrada\Tagger;

use DavidStrada\Tagger\Models\Tag;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

trait TaggableTrait
{
    /**
    * Get all of the tags for the current Model.
    * @return \Illuminate\Database\Eloquent\Relations\MorphToMany
    */
    public function tags() : MorphToMany
    {
        return $this->morphToMany(Tag::class, 'taggable')->withTimestamps();
    }

    /**
     * @param array|collection|model  tag(s) to sync to a model
     * @return  void
     * Adds a Tag(s) to a model
     */
    public function tag($tags) : void
    {
        $this->addTags($this->getWorkableTags($tags));
    }

    /**
     * Sync Tags to a Model // Increment count.
     * @param Collection $tags
     */
    private function addTags(Collection $tags) : void
    {
        $sync = $this->tags()->syncWithoutDetaching($tags);

        collect(Arr::get($sync, 'attached'))
            ->each(fn($attachedId) => $tags->where('id', $attachedId)->first()->increment('count'));
    }

    /**
     * @param  array|null
     * @return void
     */
    public function untag($tags = null) : void
    {
        if(is_null($tags)) {
            $this->removeAllTags();
            return;
        }

        $this->removeTags($this->getWorkableTags($tags));
    }

    /**
     * Ability to remove all tags at once.
     * @return void
     */
    private function removeAllTags() : void
    {
        $this->removeTags($this->tags);
    }

    /**
     * Remove tags and decrement count
     * @param  Collection $tags
     * @return void
     */
    private function removeTags(Collection $tags) : void
    {
        $this->tags()->detach($tags);
        collect($tags->where('count', '>', 0))->each(fn($tag) => $tag->decrement('count'));
    }

    /**
     * Gets Workable Tags
     * @param  array $tags
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    private function getWorkableTags($tags) : Collection
    {
        if (is_array($tags)) {
            return $this->getTagModel($tags);
        }

        if ($tags instanceof Model) {
            return $this->getTagModel([$tags->slug]);
        }

        return $this->filterTagsCollection($tags); // collections
    }

    /**
     * Ability to filter values that are not Collections.
     * @param  Collection $tags
     * @return @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    private function filterTagsCollection(Collection $tags) : Collection
    {
        return $tags->filter(fn($tag) => $tag instanceof Model);
    }

    /**
     * @param  string|array tag(s)
     * @return array
     */
    private function normalizeTags($tags) : array
    {
        return collect($tags)->map(fn ($tag) => Str::slug($tag))->unique()->toArray();
    }

    /**
     * @param  array
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    private function getTagModel(array $tags) : Collection
    {
        return Tag::whereIn('slug', $this->normalizeTags($tags))->get();
    }
}
