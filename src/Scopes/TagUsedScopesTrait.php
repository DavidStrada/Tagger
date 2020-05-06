<?php

namespace DavidStrada\Tagger\Scopes;

use Illuminate\Database\Eloquent\Builder;

trait TagUsedScopesTrait
{

    /**
     * Greater-than count
     * @param  Builder $query
     * @param  integer $count
     * @return Illuminate\Database\Eloquent\Builder;
     */
    public function scopeUsedGT(Builder $query, $count = 5) :Builder
    {
         return $this->inequalityBuilder($query, '>', $count);
    }

    /**
     * Less-than  count
     * @param  Builder $query
     * @param  integer $count
     * @return Illuminate\Database\Eloquent\Builder;
     */
    public function scopeUsedLT(Builder $query, $count = 5) :Builder
    {
         return $this->inequalityBuilder($query, '<', $count);
    }

    /**
     * Greater-than-equals  count
     * @param  Builder $query
     * @param  integer $count
     * @return Illuminate\Database\Eloquent\Builder;
     */
    public function scopeUsedGte(Builder $query, $count = 5) :Builder
    {
         return $this->inequalityBuilder($query, '>=', $count);
    }

    /**
     * Less-than-equals  count
     * @param  Builder $query
     * @param  integer $count
     * @return Illuminate\Database\Eloquent\Builder;
     */
    public function scopeUsedLte(Builder $query, $count = 5) :Builder
    {
         return $this->inequalityBuilder($query, '<=', $count);
    }

    /**
     * Inequality Builder
     * @param  Builder $query
     * @param string $sign [<, >, <=, >=]
     * @param  integer $count
     * @return Illuminate\Database\Eloquent\Builder;
     */
    private function inequalityBuilder(Builder $query, $sign, $count) :Builder
    {
        return $query->where('count', $sign, $count);
    }
}
