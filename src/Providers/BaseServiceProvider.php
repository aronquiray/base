<?php

namespace HalcyonLaravel\Base\Providers;

use Illuminate\Support\ServiceProvider;

/**
 * Class BaseServiceProvider
 *
 * @package HalcyonLaravel\Base\Providers
 */
class BaseServiceProvider extends ServiceProvider
{
    /**
     * Perform post-registration booting of services.
     *
     * @return void
     */
    public function boot()
    {
        $this->mergeConfigFrom(__DIR__.'/../../config/halcyon-laravel/base.php', 'base');
        $this->mergeConfigFrom(__DIR__.'/../../config/repository.php', 'repository');

        $this->publishes([
            __DIR__.'/../config/base.php' => config_path('base.php'),
            __DIR__.'/../config/repository.php' => config_path('repository.php'),
        ]);

        $this->loadTranslationsFrom(__DIR__.'/../resources/lang', 'base');
    }
}
