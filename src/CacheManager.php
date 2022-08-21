<?php

namespace Dinhdjj\FilamentModelWidgets;

use Closure;
use Illuminate\Cache\Repository;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Cache;

class CacheManager
{
    protected function generateCacheKey(string $key): string
    {
        return config('filament-model-widgets.cache.key').'::'.$key;
    }

    protected function getCacheRepository(): Repository
    {
        return Cache::store(config('filament-model-widgets.cache.store'));
    }

    public function remember(string $key, int $seconds, Closure $callback): mixed
    {
        $ttl = now()->addSeconds($seconds);

        return $this->getCacheRepository()->remember($this->generateCacheKey($key), $ttl, $callback);
    }

    public function rememberByQuery(Builder $query, string $postFix, int $seconds, Closure $callback): mixed
    {
        $key = sha1($query->getQuery()->toSql().json_encode($query->getQuery()->getBindings())).'.'.$postFix;

        return $this->remember($key, $seconds, $callback);
    }
}
