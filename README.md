# This is my package disk-metrics

[![Latest Version on Packagist](https://img.shields.io/packagist/v/geowrgetudor/disk-metrics.svg?style=flat-square)](https://packagist.org/packages/geowrgetudor/disk-metrics)
[![GitHub Tests Action Status](https://img.shields.io/github/actions/workflow/status/geowrgetudor/disk-metrics/run-tests.yml?branch=main&label=tests&style=flat-square)](https://github.com/geowrgetudor/disk-metrics/actions?query=workflow%3Arun-tests+branch%3Amain)
[![GitHub Code Style Action Status](https://img.shields.io/github/actions/workflow/status/geowrgetudor/disk-metrics/fix-php-code-style-issues.yml?branch=main&label=code%20style&style=flat-square)](https://github.com/geowrgetudor/disk-metrics/actions?query=workflow%3A"Fix+PHP+code+style+issues"+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/geowrgetudor/disk-metrics.svg?style=flat-square)](https://packagist.org/packages/geowrgetudor/disk-metrics)

A Laravel Pulse package that adds metrics about your storage.
Supports `local` and `s3` drivers.

-   Total Size
-   Total Files
-   Total Directories (only for `local` driver)

## Installation

You can install the package via composer:

```bash
composer require geowrgetudor/disk-metrics
```

You can publish the config file with:

```bash
php artisan vendor:publish --tag="disk-metrics-config"
```

This is the contents of the published config file:

```php
return [
    /**
     * Determines the state o the package
     */
    'enabled' => env('GEOW_DISK_METRICS', true),

    /**
     * Track disks defined in filesystems.php config file.
     * Support only 'local' or 's3' driver.
     *
     * You can pass an array ['directories', 'files'] ONLY to a local disk
     * which will determine what to be counted. To count both,
     * you can pass an empty array.
     */
    'disks' => [
        'local' => [],
        // 'public' => [],
        // 's3' => []
    ],

    /**
     * How often (in minutes) should the Laravel Pulse capture data?
     * The value should be greated than 1!
     * Default: 10
     */
    'record_interval' => 10
];
```

Optionally, you can publish the views using

```bash
php artisan vendor:publish --tag="disk-metrics-views"
```

## Usage

Register the recorder inside `config/pulse.php`. (If you don\'t have this file make sure you have published the config file of Larave Pulse using `php artisan vendor:publish --tag=pulse-config`)

```
return [
    // ...

    'recorders' => [
        // Existing recorders...

        \Geow\DiskMetrics\Recorders\DiskRecorder::class => []
    ]
]
```

Publish Laravel Pulse `dashboard.blade.php` view using `php artisan vendor:publish --tag=pulse-dashboard`

Then you can modify the file and add the disk-metrics livewire template.

```php
<livewire:disk-metrics cols="4" rows="2" />
```

## Testing

```bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Security Vulnerabilities

Please review [our security policy](../../security/policy) on how to report security vulnerabilities.

## Credits

-   [George Tudor](https://github.com/geowrgetudor)
-   [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
