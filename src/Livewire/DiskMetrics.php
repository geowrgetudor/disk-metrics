<?php

namespace Geow\DiskMetrics\Livewire;

use Geow\DiskMetrics\Facades\DiskMetrics as DiskMetricsFacade;
use Illuminate\Support\Facades\View;
use Laravel\Pulse\Facades\Pulse;
use Laravel\Pulse\Livewire\Card;
use Laravel\Pulse\Livewire\Concerns\RemembersQueries;
use Laravel\Pulse\Livewire\Concerns\HasPeriod;
use Livewire\Attributes\Lazy;

class DiskMetrics extends Card
{
    use HasPeriod;
    use RemembersQueries;

    #[Lazy]
    public function render()
    {
        $data = [];

        foreach (DiskMetricsFacade::disks() as $diskName => $diskData) {
            if ($diskData['driver'] === 's3') {
                $keys = ['total_size', 'file_count'];
            } elseif ($diskData['driver'] === 'local') {
                if (empty($diskData['resources'])) {
                    $keys = ['total_size', 'file_count', 'directory_count'];
                } else {
                    $keys = ['total_size'];
                    foreach ($diskData['resources'] as $resource) {
                        $keys[] = ["{$resource}_count"];
                    }
                }
            } else {
                throw new \Exception('Invalid disk driver');
            }

            [$dbData, $time, $runAt] = $this->remember(fn() => Pulse::values("disk_metrics_{$diskName}", $keys), "disk-metrics-{$diskName}");

            $metrics = [];

            foreach ($keys as $key) {
                $metrics[$key] = $dbData->filter(fn($item) => $item->key === $key)->first();
            }

            $data[$diskName] = [
                'disk_data' => $diskData,
                'metrics' => $metrics,
                'time' => $time,
                'run_at' => $runAt
            ];
        }

        return View::make('disk-metrics::livewire.disk-metrics', [
            'data' => $data,
        ]);
    }
}
