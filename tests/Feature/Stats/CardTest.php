<?php

use Dinhdjj\FilamentModelWidgets\Stats\Card;
use Dinhdjj\FilamentModelWidgets\Tests\Vote;
use Dinhdjj\FilamentModelWidgets\Tests\VoteFactory;
use Flowframe\Trend\Trend;

it('generate chart correctly', function (string $method) {
    VoteFactory::new()->count(100)->create();

    $card = Card::model(Vote::class, now()->subMonth(), now())->$method('score');

    $compareDate = now()->subMonth()->subSeconds(now()->subMonth()->diffInSeconds(now()));

    expect($card->getChart())->toBe(
        Trend::model(Vote::class)
        ->between($compareDate, now())
        ->perDay()
        ->$method('score')
        ->map
        ->aggregate
        ->toArray()
    );
})->with(['sum', 'average', 'count', 'max', 'min']);
