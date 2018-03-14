<?php
namespace HalcyonLaravel\Base\Providers;

use Illuminate\Support\ServiceProvider;

class BaseServiceProvider extends ServiceProvider
{
    public function boot()
    {
    }

    public function register()
    {
        $this->loadViewsFrom(__DIR__.'/views', 'base');
    }
}
