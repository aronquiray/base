<?php

namespace HalcyonLaravel\Base\Listeners;

use Spatie\MediaLibrary\Events\MediaHasBeenAdded;

class MediaHasBeenAddedListener
{
    /**
     * @param  \Spatie\MediaLibrary\Events\MediaHasBeenAdded  $event
     */
    public function handle(MediaHasBeenAdded $event)
    {
        app('query.cache')->flush();
    }
}