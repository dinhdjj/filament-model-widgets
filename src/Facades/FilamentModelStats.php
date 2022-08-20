<?php

namespace Dinhdjj\FilamentModelStats\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Dinhdjj\FilamentModelStats\FilamentModelStats
 */
class FilamentModelStats extends Facade
{
    protected static function getFacadeAccessor()
    {
        return \Dinhdjj\FilamentModelStats\FilamentModelStats::class;
    }
}
