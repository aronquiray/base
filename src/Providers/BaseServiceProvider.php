<?php

namespace HalcyonLaravel\Base\Providers;

use Illuminate\Support\ServiceProvider;

class BaseServiceProvider extends ServiceProvider
{
    /**
     * Perform post-registration booting of services.
     *
     * @return void
     */
    public function boot()
    {
        $this->mergeConfigFrom(__DIR__.'/../config/halcyon-laravel/base.php', 'base');

        $this->publishes([
            __DIR__.'/../config/halcyon-laravel/base.php' => config_path('halcyon-laravel/base.php'),
        ]);

        $this->loadTranslationsFrom(__DIR__.'/../resources/lang', 'base');
    }
}
