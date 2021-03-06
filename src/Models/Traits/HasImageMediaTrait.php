<?php

namespace HalcyonLaravel\Base\Models\Traits;

use Illuminate\Support\Collection;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;
use stdClass;

trait HasImageMediaTrait
{
    use HasMediaTrait {
        getMedia as protected getMediaOverride;
    }

    /**
     * @param  string  $collection
     * @param  string  $conversionName
     * @param  string  $field
     * @param  array  $attributes
     * @param  bool  $lazyLoad
     * @param  float|null  $width
     * @param  float|null  $height
     *
     * @return \Spatie\Html\Elements\Img
     */
    public function getFirstMediaImage(
        string $collection = 'images',
        string $conversionName = '',
        string $field = 'title',
        array $attributes = [],
        bool $lazyLoad = true
    ) {

        $media = $this->getFirstMedia($collection);

        if (empty($media)) {
            $attributes = [
                    'title' => $this->{$field},
                ] + $attributes;

            $src = null;
//            if (!is_null($width) && !is_null($height)) {
//                $src = dummy_image($width, $height, $attributes['title']);
//            }
            if (method_exists($this, 'mediaDefaultSizes')) {
                $sizes = $this->mediaDefaultSizes();
                if (isset($sizes[$collection][$conversionName])) {
                    $size = $sizes[$collection][$conversionName];
                    $src = dummy_image($size['width'], $size['height'], $attributes['title']);
                }
            }

            return html()->img($src, $this->{$field})->attributes($attributes);
        }

        return $media->getMediaImage($conversionName, $field, $attributes, $lazyLoad);
    }

    /**
     * @param  string  $collectionName
     * @param  string  $conversionName
     *
     * @return string
     */
    public function getFirstMediaBase64(string $collectionName, string $conversionName = '')
    {
        $media = $this->getFirstMedia($collectionName);

        // TODO: double check getting file
        $path = storage_path(
            'app/public/images/'.
            md5($media->id).
            '/c/'.
            $media->name.
            '-'.
            $conversionName.
            '.'.
            (explode('.', $media->file_name)[1])
        );
//        $path = $media->getPath();

        $type = pathinfo($path, PATHINFO_EXTENSION);
        $data = file_get_contents($path);
        return 'data:application/'.$type.';base64,'.base64_encode($data);
    }

    /**
     * Return an array of image objects.
     * Used in image uploader.
     *
     * @param  string  $collection_name
     * @param  string  $conversion
     *
     * @return mixed
     */
    public function getUploaderImages(string $collection_name = 'images', string $conversion = '')
    {
        $defaultAllowedProperties = ["attributes"];

        $allowedProperties = defined('self::MEDIA_LIBRARY_CUSTOM_PROPERTIES') ? self::MEDIA_LIBRARY_CUSTOM_PROPERTIES : [];
        $allowedProperties = array_merge($allowedProperties, $defaultAllowedProperties);

        return $this->getMedia($collection_name)->map(function ($media) use ($conversion, $allowedProperties) {
            $obj = new StdClass;

            $obj->id = $media->id;
            $obj->name = $media->file_name;
            $obj->source = $media->getUrl(($media->mime_type == 'image/x-icon') ? '' : $conversion);
            $obj->thumbnail = $media->getUrl(($media->mime_type == 'image/x-icon') ? '' : 'thumbnail');
            $obj->deleteUrl = route('webapi.admin.image.destroy', $media);
            $obj->updatePropertyUrl = route('webapi.admin.image.update.property', $media);
            $obj->properties = $this->formatCustomProperties($media->custom_properties, $allowedProperties);

            return $obj;
        });
    }

    /**
     * Get media collection by its collectionName.
     *
     * @param  string  $collectionName
     * @param  array|callable  $filters
     *
     * @return \Illuminate\Support\Collection
     * @override
     */
    public function getMedia(string $collectionName = 'default', $filters = []): Collection
    {
        if (app()->runningInConsole()) {
            return $this->getMediaOverride($collectionName, $filters);
        }

        return app('query.cache')->queryCache(
            [get_class($this), $this->id, $collectionName, implode(',', $filters)],
            function () use ($collectionName, $filters) {
                return $this->getMediaOverride($collectionName, $filters);
            }
        );
    }

    /**
     * @param  array  $customProperties
     * @param  array  $allowedProperties
     *
     * @return \StdClass
     */
    private function formatCustomProperties(array $customProperties, array $allowedProperties)
    {
        $properties = new StdClass;

        foreach ($allowedProperties as $property) {
            $properties->{$property} = $customProperties[$property] ?? '';
        }

        return $properties;
    }
}