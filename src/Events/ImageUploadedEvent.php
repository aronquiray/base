<?php

namespace HalcyonLaravel\Base\Events;

use Spatie\MediaLibrary\HasMedia\HasMedia;
use Spatie\MediaLibrary\Models\Media;

abstract class ImageUploadedEvent
{
    /**
     * @var \Spatie\MediaLibrary\HasMedia\HasMedia
     */
    protected $model;
    /**
     * @var \Spatie\MediaLibrary\Models\Media
     */
    protected $media;

    /**
     * @var string
     */
    protected $action;

    public function __construct(HasMedia $model, Media $media)
    {
        $this->model = $model;
        $this->media = $media;
    }

    public function getModel(): HasMedia
    {
        return $this->model;
    }

    public function getMedial(): Media
    {
        return $this->media;
    }

    /**
     * @return string
     */
    public function getAction(): string
    {
        return $this->action;
    }

}