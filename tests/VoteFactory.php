<?php

namespace Dinhdjj\FilamentModelWidgets\Tests;

use Illuminate\Database\Eloquent\Factories\Factory;

class VoteFactory extends Factory
{
    protected $model = Vote::class;

    public function definition()
    {
        return [
            'score' => $this->faker->numberBetween(1, 10),
            'created_at' => $this->faker->dateTimeBetween('-2 month', '-1 day'),
        ];
    }
}
