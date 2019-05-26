<?php

namespace HalcyonLaravel\Base\Models;

class Media extends \Spatie\MediaLibrary\Models\Media
{

    public function getMediaImage(
//        string $collection = 'images',
        string $conversionName = '',
        string $field = 'title',
        array $attributes = [],
        bool $lazyLoad = true
    ) {
        $media = $this;

        $attributeString = collect($media->getCustomProperty('attributes') + $attributes)
            ->map(function ($value, $name) use ($lazyLoad) {
                if ($name == 'class' && $lazyLoad) {
                    return $name.'="lazy '.$value.'"';
                }
                return $name.'="'.$value.'"';
            })->implode(' ');

        $src = $media->getUrl($conversionName);

        // testing propose
//        $file = $media->getPath($conversionName);
//        if (file_exists($file)) {
//            $width = Image::load($file)->getWidth();
//            $height = Image::load($file)->getHeight();
//            $src = dummy_image($width, $height, 'f6de3d', '007ac3', $width.'x'.$height);
//        }

        if ($lazyLoad) {
            return <<<EOT
<img data-src="{$src}" {$attributeString} />
EOT;
        }
        return <<<EOT
<img src="{$src}" {$attributeString} />
EOT;
    }

    public function getUrl(string $conversionName = ''): string
    {
        if ($this->disk == 'local') {
            return route('media.private', $this);
        }
        return parent::getUrl($conversionName);
    }
}