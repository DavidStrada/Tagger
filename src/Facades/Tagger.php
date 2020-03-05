<?php

namespace DavidStrada\Tagger\Facades;

use Illuminate\Support\Facades\Facade;

class Tagger extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'tagger';
    }
}
