<?php

namespace Dinhdjj\FilamentModelStats;

use Filament\PluginServiceProvider;
use Spatie\LaravelPackageTools\Package;

class FilamentModelStatsServiceProvider extends PluginServiceProvider
{
    public function configurePackage(Package $package): void
    {
        /*
         * This class is a Package Service Provider
         *
         * More info: https://github.com/spatie/laravel-package-tools
         */
        $package
            ->name('filament-model-stats')
            ->hasTranslations();
    }
}
