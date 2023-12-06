<?php

namespace Geow\DiskMetrics;

use Geow\DiskMetrics\Livewire\DiskMetrics;
use Illuminate\Foundation\Application;
use Livewire\LivewireManager;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class DiskMetricsServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package
            ->name('disk-metrics')
            ->hasConfigFile()
            ->hasViews();
    }

    public function boot(): void
    {
        parent::boot();

        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'disk-metrics');

        $this->callAfterResolving('livewire', function (LivewireManager $livewire, Application $app) {
            $livewire->component('disk-metrics', DiskMetrics::class);
        });
    }
}
