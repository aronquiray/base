<?php

namespace HalcyonLaravel\Base\Providers;

use HalcyonLaravel\Base\QueryCacheModelRepositoryHelper;
use Illuminate\Database\Schema\Blueprint;
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
        $this->mergeConfigFrom(__DIR__.'/../../config/base.php', 'base');
        $this->mergeConfigFrom(__DIR__.'/../../config/repository.php', 'repository');

        $this->publishes([
            __DIR__.'/../../config/base.php' => config_path('base.php'),
            __DIR__.'/../../config/repository.php' => config_path('repository.php'),
        ]);

        $this->loadTranslationsFrom(__DIR__.'/../resources/lang', 'base');
    }

    public function register()
    {
        $this->app->bind('query.cache', function () {
            return new QueryCacheModelRepositoryHelper;
        });

        if (app()->runningInConsole()) {
            $this->bluePrintMacros();
        }
    }

    private function bluePrintMacros()
    {
        $foreignConstraint = function (
            bool $isRelationBigInteger,
            Blueprint $blueprint,
            $column,
            string $foreignTableName,
            string $onDelete = null
        ) {
            $tbl = $blueprint
                ->{($isRelationBigInteger ? 'bigInteger' : 'integer')}($column)
                ->unsigned();

            if (is_null($onDelete)) {

                $blueprint->foreign($column)
                    ->references('id')
                    ->on($foreignTableName);
            } else {

                $blueprint->foreign($column)
                    ->references('id')
                    ->on($foreignTableName)
                    ->onDelete($onDelete);
            }
            return $tbl;
        };
        Blueprint::macro('foreignConstraintBigInteger', function (
            $column,
            string $foreignTableName,
            string $onDelete = null
        ) use ($foreignConstraint) {
            return $foreignConstraint(true, $this, $column, $foreignTableName, $onDelete);
        });
        Blueprint::macro('foreignConstraint', function (
            $column,
            string $foreignTableName,
            string $onDelete = null
        ) use ($foreignConstraint) {
            return $foreignConstraint(false, $this, $column, $foreignTableName, $onDelete);
        });

        Blueprint::macro('jsonable', function ($column) {
            $type = is_latest_mysql_version() ? 'json' : 'longText';
            return $this->$type($column);
        });
    }
}
