<?php

namespace Geow\DiskMetrics\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Geow\DiskMetrics\DiskMetrics
 */
class DiskMetrics extends Facade
{
    protected static function getFacadeAccessor()
    {
        return \Geow\DiskMetrics\DiskMetrics::class;
    }
}
