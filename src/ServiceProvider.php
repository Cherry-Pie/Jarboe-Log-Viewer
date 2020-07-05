<?php

namespace Yaro\JarboeLogViewer;

use Illuminate\Support\ServiceProvider as IlluminateServiceProvider;

class ServiceProvider extends IlluminateServiceProvider
{
    /**
     * Perform post-registration booting of services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__ .'/resources/views' => base_path('resources/views/vendor/log-viewer/jarboe'),
            __DIR__ .'/config' => base_path('config'),
        ]);
    }
}
