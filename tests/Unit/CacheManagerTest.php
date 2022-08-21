<?php

use Dinhdjj\FilamentModelWidgets\Facades\CacheManager;
use Dinhdjj\FilamentModelWidgets\Tests\Vote;
use Illuminate\Support\Facades\Cache;
use function Spatie\PestPluginTestTime\testTime;

beforeEach(function () {
    $this->baseKey = config('filament-model-widgets.cache.key').'::';
    $this->cacheRepository = Cache::store(config('filament-model-widgets.cache.store'));
});

it('can remember', function () {
    testTime()->freeze();

    CacheManager::remember('test', 60, function () {
        return 'test';
    });

    expect($this->cacheRepository->get($this->baseKey.'test'))->toBe('test');

    testTime()->addSeconds(60);

    expect($this->cacheRepository->get($this->baseKey.'test'))->toBe('test');

    testTime()->addSeconds(1);
    expect($this->cacheRepository->get($this->baseKey.'test'))->toBe(null);
});

it('can remember by query', function () {
    testTime()->freeze();
    $query = Vote::query()->where('id', 1)->where('created_at', '>=', now()->subSeconds(60));
    $key = $this->baseKey.sha1($query->getQuery()->toSql().json_encode($query->getQuery()->getBindings())).'.'.'test';

    CacheManager::rememberByQuery($query, 'test', 60, function () {
        return 'test';
    });

    expect($this->cacheRepository->get($key))->toBe('test');

    testTime()->addSeconds(60);

    expect($this->cacheRepository->get($key))->toBe('test');

    testTime()->addSeconds(1);
    expect($this->cacheRepository->get($key))->toBe(null);
});
