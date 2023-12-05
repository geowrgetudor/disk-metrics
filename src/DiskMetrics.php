<?php

namespace Geow\DiskMetrics;

use Exception;

class DiskMetrics
{
    public static function interval(): int
    {
        $intervalInMinutes = config('disk-metrics.record_interval', 10);

        if (!is_int($intervalInMinutes)) {
            throw new Exception("Invalid interval format. 'disk-metrics.record_inteval' should be integer.");
        }

        if ($intervalInMinutes <= 1) {
            $intervalInMinutes = 10;
        }

        return $intervalInMinutes;
    }

    public static function disks(): array
    {
        $disks = config('disk-metrics.disks', []);

        if (!is_array($disks)) {
            throw new Exception("Invalid disks format. 'disk-metrics.disks' should be array.");
        }

        $filesystemDisks = [];

        foreach ($disks as $disk => $resources) {
            $filesystemDisk = config("filesystems.disks.{$disk}");

            if (!$filesystemDisk) {
                throw new Exception("Invalid disk driver. Make sure '{$disk}' is defined inside filesystems.php config file and it uses 'local' or 's3' driver.");
            }

            if (!in_array($filesystemDisk['driver'], ['local', 's3'], true)) {
                throw new Exception('Invalid disk driver. Supported disk drivers: "local", "s3"');
            }

            if ($filesystemDisk['driver'] === 'local' && !is_array($resources)) {
                throw new Exception('Invalid disk settings. Please check "disk-metrics.php" config file for guidance.');
            }

            $filesystemDisks[$disk] = [
                'driver' => $filesystemDisk['driver'],
                'root' => $filesystemDisk['root'] ?? null,
                'bucket' => $filesystemDisk['bucket'] ?? null,
                'resources' => $resources,
            ];
        }

        return $filesystemDisks;
    }
}
