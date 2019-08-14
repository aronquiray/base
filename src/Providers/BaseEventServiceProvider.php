<?php

namespace HalcyonLaravel\Base\Providers;

use HalcyonLaravel\Base\Listeners\BaseCleanCacheRepository;
use HalcyonLaravel\Base\Listeners\MediaCollectionHasBeenClearedListener;
use HalcyonLaravel\Base\Listeners\MediaHasBeenAddedListener;
use Illuminate\Foundation\Support\Providers\EventServiceProvider;
use Prettus\Repository\Events\RepositoryEntityCreated;
use Prettus\Repository\Events\RepositoryEntityDeleted;
use Prettus\Repository\Events\RepositoryEntityUpdated;
use Spatie\MediaLibrary\Events\CollectionHasBeenCleared;
use Spatie\MediaLibrary\Events\MediaHasBeenAdded;

class BaseEventServiceProvider extends EventServiceProvider
{
    protected $listen = [
        RepositoryEntityCreated::class => [
            BaseCleanCacheRepository::class,
        ],
        RepositoryEntityDeleted::class => [
            BaseCleanCacheRepository::class,
        ],
        RepositoryEntityUpdated::class => [
            BaseCleanCacheRepository::class,
        ],
        MediaHasBeenAdded::class => [
            MediaHasBeenAddedListener::class,
        ],
        CollectionHasBeenCleared::class => [
            MediaCollectionHasBeenClearedListener::class,
        ],
    ];
    protected $subscribe = [
    ];

    public function boot()
    {
        parent::boot();
        //
    }
}