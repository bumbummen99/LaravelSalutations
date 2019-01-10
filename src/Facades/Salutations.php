<?php

namespace SkyRaptor\LaravelSalutations\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * SkyRaptor\LaravelSalutations\Facades\Salutation
 *
 */
class Salutations extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor() { return 'salutations'; }
}