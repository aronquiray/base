<?php

namespace HalcyonLaravel\Base\Events;

class ImageUploadedEvent extends ImageUploaderEvent
{
    protected $action = "uploaded";
}