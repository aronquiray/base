<?php

namespace HalcyonLaravel\Base\Events;

class ImagedUploadedEvent extends ImageUploadedEvent
{
    protected $action = "uploaded";
}