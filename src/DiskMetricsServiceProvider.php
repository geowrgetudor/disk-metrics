<?php

namespace Geow\DiskMetrics;

use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use Geow\DiskMetrics\Commands\DiskMetricsCommand;

class DiskMetricsServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        /*
         * This class is a Package Service Provider
         *
         * More info: https://github.com/spatie/laravel-package-tools
         */
        $package
            ->name('disk-metrics')
            ->hasConfigFile()
            ->hasViews()
            ->hasMigration('create_disk-metrics_table')
            ->hasCommand(DiskMetricsCommand::class);
    }
}
