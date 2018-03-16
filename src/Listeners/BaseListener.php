<?php

namespace HalcyonLaravel\Base\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

use HalcyonLaravel\Base\Events\BaseStoringEvent;
use Log;

class BaseListener
{
    public function onStoring($event)
    {
        Log::info('Storing');
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
    }
}
