# Log Viewer for Jarboe

Integrate [ArcaneDev/LogViewer](https://github.com/ARCANEDEV/LogViewer) in [Jarboe](https://github.com/Yaro/Jarboe) project.

![JarboeLogViewer](https://user-images.githubusercontent.com/3027596/86470799-d5c36680-bd44-11ea-9048-3401df4d378d.png)

## Installation

Install package via Composer:
```bash
composer require yaro/jarboe-log-viewer
```
Publish config file and views:
```bash
php artisan vendor:publish --provider="Yaro\JarboeLogViewer\ServiceProvider"
```

In `config/logging.php` make sure `daily` channel is within channels of `stack` log channel, that is used by default in "pure" Laravel application, e.g.:
```php
// config/logging.php

return [
   //...
  'channels' => [
    'stack' => [
      'driver' => 'stack',
      'name' => 'channel-name',
      'channels' => ['daily', 'slack'],
    ],

    'daily' => [
        'driver' => 'daily',
        'path' => storage_path('logs/laravel.log'),
        'level' => 'debug',
        'days' => 7,
    ],

    'slack' => [
      'driver' => 'slack',
      'url' => env('LOG_SLACK_WEBHOOK_URL'),
      'level' => 'critical',
    ],
    //...
];
```

## Usage

Logs can be viewed on `log-viewer` under your admin panel prefix, by default it will be `http://localhost/admin/log-viewer`.

Look at `config/log-viewer.php` for more configuration options.


## License
[MIT](LICENSE.md)
