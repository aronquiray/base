<?php


namespace HalcyonLaravel\Base\Listeners;


use Spatie\MediaLibrary\Events\CollectionHasBeenCleared;

class MediaCollectionHasBeenClearedListener
{
    /**
     * @param  \Spatie\MediaLibrary\Events\CollectionHasBeenCleared  $event
     */
    public function handle(CollectionHasBeenCleared $event)
    {
        app('query.cache')->flush();
    }

}