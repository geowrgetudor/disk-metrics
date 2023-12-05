<?php

namespace Geow\DiskMetrics\Commands;

use Illuminate\Console\Command;

class DiskMetricsCommand extends Command
{
    public $signature = 'disk-metrics';

    public $description = 'My command';

    public function handle(): int
    {
        $this->comment('All done');

        return self::SUCCESS;
    }
}
