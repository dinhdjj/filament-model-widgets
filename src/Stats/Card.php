<?php

namespace Dinhdjj\FilamentModelWidgets\Stats;

use Carbon\Carbon;
use Dinhdjj\FilamentModelWidgets\Facades\CacheManager;
use Filament\Widgets\StatsOverviewWidget\Card as BaseCard;
use Flowframe\Trend\Trend;
use Illuminate\Database\Eloquent\Builder;
use InvalidArgumentException;
use ReflectionClass;

class Card
{
    protected BaseCard $card;

    protected int $secondsToCache = 0;

    protected Carbon $comparedDate;

    public function __construct(
        protected Builder $query,
        protected Carbon $start,
        protected Carbon $end,
        protected string $chartPeriod = 'day'
    ) {
        if ($end->lte($start)) {
            throw new InvalidArgumentException('End date must be greater than start date');
        }

        if (! in_array($chartPeriod, ['minute', 'hour', 'day', 'month', 'year'])) {
            throw new InvalidArgumentException('Chart period must be one of minute, hour, day, month, year');
        }

        $this->comparedDate = $start->copy()->subSeconds($start->diffInSeconds($end));
    }

    public static function model(
        string $model,
        Carbon $start,
        Carbon $end,
        string $chartPeriod = 'day'
    ): static {
        /** @phpstan-ignore-next-line */
        return new static($model::query(), $start, $end, $chartPeriod);
    }

    public static function query(
        Builder $query,
        Carbon $start,
        Carbon $end,
        string $chartPeriod = 'day'
    ): static {
        /** @phpstan-ignore-next-line */
        return new static($query, $start, $end, $chartPeriod);
    }

    /**
     * Cache the result of the queries for the given amount of seconds.
     *
     * @return $this
     */
    public function cache(int $seconds = 300): static
    {
        $this->secondsToCache = $seconds;

        return $this;
    }

    /**
     * Generate the card for average $column.
     *
     * @param  callable(float): string  $displaceValue
     */
    public function average(string $column, ?string $label = null, ?callable $displaceValue = null): BaseCard
    {
        return $this->generate('average', $column, $label, $displaceValue);
    }

    /**
     * Generate the card for max $column.
     *
     * @param  callable(float): string  $displaceValue
     */
    public function max(string $column, ?string $label = null, ?callable $displaceValue = null): BaseCard
    {
        return $this->generate('max', $column, $label, $displaceValue);
    }

    /**
     * Generate the card for min $column.
     *
     * @param  callable(float): string  $displaceValue
     */
    public function min(string $column, ?string $label = null, ?callable $displaceValue = null): BaseCard
    {
        return $this->generate('min', $column, $label, $displaceValue);
    }

    /**
     * Generate the card for count $column.
     *
     * @param  callable(float): string  $displaceValue
     */
    public function count(string $column = '*', ?string $label = null, ?callable $displaceValue = null): BaseCard
    {
        return $this->generate('count', $column, $label, $displaceValue);
    }

    /**
     * Generate the card for sum $column.
     *
     * @param  callable(float): string  $displaceValue
     */
    public function sum(string $column, ?string $label = null, ?callable $displaceValue = null): BaseCard
    {
        return $this->generate('sum', $column, $label, $displaceValue);
    }

    protected function generate(string $method, string $column, ?string $label = null, ?callable $displaceValue = null): BaseCard
    {
        // Automatically set the label if none is provided.
        if ($column === '*') {
            $class = new ReflectionClass($this->query->getModel());
            $label ??= __(ucfirst($method).' '.ucfirst($class->getShortName()));
        } else {
            $label ??= __(ucfirst($method).' '.ucfirst($column));
        }

        // Calculate old value
        $oldValueQuery = $this
            ->query
            ->clone()
            ->where('created_at', '>=', $this->comparedDate)
            ->where('created_at', '<', $this->start);

        $oldValue = (float) CacheManager::rememberByQuery($oldValueQuery, $method, $this->secondsToCache, function () use ($oldValueQuery, $method, $column) {
            return $oldValueQuery->$method($column);
        });

        // Calculate new value
        $newValueQuery = $this
            ->query
            ->clone()
            ->where('created_at', '>=', $this->start)
            ->where('created_at', '<=', $this->end);
        $newValue = (float) CacheManager::rememberByQuery($newValueQuery, $method, $this->secondsToCache, function () use ($newValueQuery, $method, $column) {
            return $newValueQuery->$method($column);
        });

        // Calculate trend
        $per = 'per'.ucfirst($this->chartPeriod);
        $chart = CacheManager::rememberByQuery($this->query, "trend-{$method}-{$per}", $this->secondsToCache, function () use ($method, $column, $per) {
            return Trend::query($this->query)
            ->between($this->comparedDate, $this->end)
            ->$per()
            ->$method($column)
            ->map
            ->aggregate
            ->toArray();
        });

        // Create card'
        $defaultValue = rtrim(number_format($newValue, 1), '0.');
        $card = BaseCard::make($label, value($displaceValue ?? $defaultValue, $newValue))
            ->chart($chart);
        $this->addDescriptionWithTrendingToCard($card, $oldValue, $newValue);

        return $card;
    }

    protected function addDescriptionWithTrendingToCard(BaseCard $card, float $oldValue, float $newValue): void
    {
        if (0.0 !== $oldValue) {
            $percentage = $newValue / $oldValue * 100;

            if ($newValue > $oldValue) {
                $percentageIncrease = $percentage - 100;
                $card->description(__(':percentage% increase', ['percentage' => number_format($percentageIncrease, 1)]))
                    ->descriptionIcon('heroicon-o-trending-up')
                    ->color('success');
            } elseif ($newValue < $oldValue) {
                $percentageDecrease = 100 - $percentage;
                $card->description(__(':percentage% decrease', ['percentage' => number_format($percentageDecrease, 1)]))
                    ->descriptionIcon('heroicon-o-trending-down')
                    ->color('danger');
            }
        }
    }
}
