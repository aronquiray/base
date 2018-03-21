<?php

namespace HalcyonLaravel\Base\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

use HalcyonLaravel\Base\Events\BaseStoringEvent;
use HalcyonLaravel\Base\Events\BaseStoredEvent;

use HalcyonLaravel\Base\Events\BaseUpdatingEvent;
use HalcyonLaravel\Base\Events\BaseUpdatedEvent;

use HalcyonLaravel\Base\Events\BaseDeletingEvent;
use HalcyonLaravel\Base\Events\BaseDeletedEvent;

use HalcyonLaravel\Base\Events\BaseRestoringEvent;
use HalcyonLaravel\Base\Events\BaseRestoredEvent;

use HalcyonLaravel\Base\Events\BasePurgingEvent;
use HalcyonLaravel\Base\Events\BasePurgedEvent;

use Log;

class BaseListener
{
    public function onStoring($event)
    {
        Log::info('Storing');
    }

    public function onStored($event)
    {
        Log::info('Stored');
    }

    public function onUpdating($event)
    {
        Log::info('Updating');
    }

    public function onUpdated($event)
    {
        Log::info('Updated');
    }

    public function onDeleting($event)
    {
        Log::info('Deleting');
    }

    public function onDeleted($event)
    {
        Log::info('Deleted');
    }

    public function onRestoring($event)
    {
        Log::info('Restoring');
    }

    public function onRestored($event)
    {
        Log::info('Restored');
    }

    public function onPurging($event)
    {
        Log::info('Purging');
    }

    public function onPurged($event)
    {
        Log::info('Purged');
    }

    /**
      * Register the listeners for the subscriber.
      *
      * @param \Illuminate\Events\Dispatcher $events
      */
    public function subscribe($events)
    {
        $nameSpace = 'HalcyonLaravel\Base\Listeners\BaseListener@';

        $events->listen(
            BaseStoringEvent::class,
            $nameSpace . 'onStoring'
        );
        $events->listen(
            BaseStoredEvent::class,
            $nameSpace . 'onStored'
        );

        $events->listen(
            BaseUpdatingEvent::class,
            $nameSpace . 'onUpdating'
        );
        $events->listen(
            BaseUpdatedEvent::class,
            $nameSpace . 'onUpdated'
        );

        $events->listen(
            BaseDeletingEvent::class,
            $nameSpace . 'onDeleting'
        );
        $events->listen(
            BaseDeletedEvent::class,
            $nameSpace . 'onDeleted'
        );

        $events->listen(
            BaseRestoringEvent::class,
            $nameSpace . 'onRestoring'
        );
        $events->listen(
            BaseRestoredEvent::class,
            $nameSpace . 'onRestored'
        );

        $events->listen(
            BasePurgingEvent::class,
            $nameSpace . 'onPurging'
        );
        $events->listen(
            BasePurgedEvent::class,
            $nameSpace . 'onPurged'
        );
    }
}
