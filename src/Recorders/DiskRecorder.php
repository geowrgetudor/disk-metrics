<?php

namespace Geow\DiskMetrics\Recorders;

use Geow\DiskMetrics\Facades\DiskMetrics;
use Illuminate\Config\Repository;
use Illuminate\Support\Facades\Process;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Number;
use Laravel\Pulse\Events\SharedBeat;
use Laravel\Pulse\Pulse;

class DiskRecorder
{
    public string $listen = SharedBeat::class;

    public function __construct(
        protected Pulse $pulse,
        protected Repository $config
    ) {
    }

    public function record(SharedBeat $event): void
    {
        if (!config('disk-metrics.enabled', false)) {
            return;
        }

        if ($event->time->minute % DiskMetrics::interval() !== 0) {
            return;
        }

        foreach (DiskMetrics::disks() as $diskName => $diskData) {
            if ($diskData['driver'] === 'local') {
                $commands = collect([
                    'total_size' => "du -sh {$diskData['root']}"
                ]);

                $resources = collect($diskData['resources'])
                    ->reject(fn($type) => !in_array($type, ['file', 'directory'], true))
                    ->each(fn($type) => $commands->put("{$type}_count", "find {$diskData['root']} -type {$type[0]} | wc -l"));

                if ($resources->isEmpty()) {
                    $commands = $commands->merge([
                        'file_count' => "find {$diskData['root']} -type f | wc -l",
                        'directory_count' => "find {$diskData['root']} -type d | wc -l"
                    ]);
                }

                $commands->each(function ($command, $metricName) use ($diskName, $diskData, $event) {
                    $result = Process::run($command);

                    if ($result->failed()) {
                        return true;
                    }

                    $this->pulse->set(
                        "disk_metrics_{$diskName}",
                        $metricName,
                        str($result->output())->replace($diskData['root'], '')->trim()->toString(),
                        $event->time
                    );
                });
            }

            if ($diskData['driver'] === 's3') {
                $client = Storage::disk($diskName)->getClient();

                $totalSize = 0;
                $totalCount = 0;

                $options = [
                    'Bucket' => $diskData['bucket']
                ];

                do {
                    $result = $client->listObjectsV2($options);

                    foreach ($result['Contents'] as $object) {
                        $totalSize += $object['Size'];
                        $totalCount++;
                    }

                    $options['ContinuationToken'] = $result['NextContinuationToken'] ?? null;
                } while ($result['IsTruncated']);

                $this->pulse->set(
                    "disk_metrics_{$diskName}",
                    'file_count',
                    $totalCount,
                    $event->time
                );

                $this->pulse->set(
                    "disk_metrics_{$diskName}",
                    'total_size',
                    Number::fileSize($totalSize),
                    $event->time
                );
            }
        }
    }
}
