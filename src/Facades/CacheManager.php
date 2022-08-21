<?php

namespace Dinhdjj\FilamentModelWidgets\Facades;

use Dinhdjj\FilamentModelWidgets\CacheManager as FilamentModelWidgetsCacheManager;
use Illuminate\Support\Facades\Facade;

/**
 * @method static mixed remember(string $key, int $seconds, Closure $callback)
 * @method static mixed rememberByQuery(\Illuminate\Database\Eloquent\Builder $query, string $postFix, int $seconds, Closure $callback) Remember but used query for key
 */
class CacheManager extends Facade
{
    protected static function getFacadeAccessor()
    {
        return FilamentModelWidgetsCacheManager::class;
    }
}
