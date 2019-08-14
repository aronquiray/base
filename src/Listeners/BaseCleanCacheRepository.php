<?php

namespace HalcyonLaravel\Base\Listeners;

use Prettus\Repository\Events\RepositoryEventBase;

class BaseCleanCacheRepository
{
    /**
     * @param  \Prettus\Repository\Events\RepositoryEventBase  $event
     */
    public function handle(RepositoryEventBase $event)
    {
        app('query.cache')->flush();
    }
}