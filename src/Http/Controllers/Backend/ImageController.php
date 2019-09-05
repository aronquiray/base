<?php

namespace HalcyonLaravel\Base\Http\Controllers\Backend;

use HalcyonLaravel\Base\Events\ImageUploadedEvent;
use HalcyonLaravel\Base\Http\Controllers\Backend\Contracts\ImageContract;
use HalcyonLaravel\Base\Http\Controllers\Controller;
use HalcyonLaravel\Base\Models\Contracts\BaseModelInterface;
use HalcyonLaravel\Base\Models\Model as HalcyonBaseModel;
use Illuminate\Http\Request;
use InvalidArgumentException;
use Spatie\MediaLibrary\HasMedia\HasMedia;
use Spatie\MediaLibrary\Models\Media;
use StdClass;

/**
 * Class ImageController
 *
 * @package App\Http\Controllers\WebApi\Backend\Image
 */
abstract class ImageController extends Controller implements ImageContract
{
    /**
     * @param  \Illuminate\Http\Request  $request
     *
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function order(Request $request)
    {
        $requestData = $this->validate($request, [
            'orderedIds.*' => 'required|integer'
        ]);

        Media::setNewOrder($requestData['orderedIds']);

        app('query.cache')->flush();

        return response()->json([
            'status' => 'success',
            'message' => 'done order',
        ], 200);
    }

    /**
     * @param  \Spatie\MediaLibrary\Models\Media  $media
     *
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function destroy(Media $media)
    {
        $model = $media->model;
        $modelClassName = get_class($model);
        $mediaCount = $model->getMedia($media->collection_name)->count();

        if ($mediaCount > 1) {
            return $this->processDelete($media);
        }

        if (
            array_key_exists($modelClassName, $this->noneRequiredModels()) &&
            (
                $this->noneRequiredModels()[$modelClassName] == '*' OR
                in_array($media->collection_name, $this->noneRequiredModels()[$modelClassName])
            )
        ) {
            return $this->processDelete($media);
        }

        $collectionName = 'all collection names';
        if (
            array_key_exists($modelClassName, $this->noneRequiredModels()) &&
            $this->noneRequiredModels()[$modelClassName] != '*'
        ) {
            $collectionName = "collection name [{$media->collection_name}].";
        }

        if ($model instanceof HalcyonBaseModel) {
            $modelClassName = $model::MODULE_NAME;
        }

        return response()->json([
            'status' => 'failed',
            'message' => "Image required in [{$modelClassName}] with [{$collectionName}].",
        ], 422);
    }

    /**
     * @param  \Spatie\MediaLibrary\Models\Media  $media
     *
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    private function processDelete(Media $media)
    {
        $media->delete();
        app('query.cache')->flush();
        return response()->json([], 204);
    }


    /**
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $routeKeyValue
     *
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function upload(Request $request, string $routeKeyValue)
    {
        $requestData = $this->validate($request, [
            'model' => 'required|in:'.$this->allowedModels(),
            'collection' => 'nullable|string',
            'conversion' => 'nullable|string',
            'is_single' => 'nullable|bool',
        ]);

        $model = $this->getModel($requestData['model'], $routeKeyValue);

        $collectionName = $requestData['collection'] ?? $model->collection_name ?? 'images';
        $conversion = $requestData['conversion'] ?? '';

        $rules = isset($this->validations()[$this->models()[$requestData['model']]]) ? $this->validations()[$this->models()[$requestData['model']]] : [];

        if (is_array($rules)) {
            if (!empty($rules)) {
                if (array_key_exists('*', $rules)) {
                    $rules = $rules['*'];
                } elseif (array_key_exists($collectionName, $rules)) {
                    $rules = $rules[$collectionName];
                } else {
                    $rules = null;
                }
            }
        } else {
            throw  new InvalidArgumentException('Invalid argument, it must me an array.');
        }

        $requestImage = $this->validate($request, [
            'image' => $rules ?: 'required|image',
        ])['image'];

        abort_if(is_null($collectionName), 422, 'No collection name specified, aborted.');

        if ($model instanceof BaseModelInterface) {
            $fileName = $model->base();
        } else {
            $fileName = $this->getFileName($requestImage->getClientOriginalName());
        }

        $media = $model->addMedia($requestImage)
            ->withCustomProperties([
                'attributes' => [
                    'alt' => $fileName,
                    'title' => $fileName,
                ]
            ])
//            ->usingFileName($fileName.'.'.$requestImage->clientExtension())
            ->toMediaCollection($collectionName);

        $this->checkClearOtherMedia($model, $media, $collectionName, $requestData);

        $image = $this->convertToUploaderImageFormat($media, $conversion,
            defined($this->models()[$requestData['model']].'::MEDIA_LIBRARY_CUSTOM_PROPERTIES')
                ? array_merge(['attributes'], $model::MEDIA_LIBRARY_CUSTOM_PROPERTIES)
                : ['attributes']
        );

        event(new ImageUploadedEvent($model, $media));

        return response()->json([
            'status' => 'success',
            'data' => $image,
            'message' => 'Success upload for ['.$requestData['model'].'] with collection name ['.$collectionName.']',
        ], 200);
    }

    /**
     * @return string
     */
    private function allowedModels(): string
    {
        return implode(',', array_keys($this->models()));
    }


    /**
     * @param  string  $modelName
     * @param  string  $routeKeyValue
     *
     * @return \Spatie\MediaLibrary\HasMedia\HasMedia
     */
    private function getModel(string $modelName, string $routeKeyValue): HasMedia
    {
        $model = app($this->models()[$modelName]);

        return $model::where($model->getRouteKeyName(), $routeKeyValue)->firstOrFail();
    }


    /**
     * @param  string  $fileName_
     *
     * @return string
     */
    private function getFileName(string $fileName_): string
    {
        $fileName = explode('/', $fileName_);
        $fileName = $fileName[count($fileName) - 1];
        $fileName = explode('.', $fileName)[0];
        $fileName = str_replace('%20', ' ', $fileName);
        $fileName = str_replace('-', ' ', $fileName);
        return str_replace('_', ' ', $fileName);
    }

    /**
     * @param  \Spatie\MediaLibrary\HasMedia\HasMedia  $model
     * @param  \Spatie\MediaLibrary\Models\Media  $media
     * @param  string  $collectionName
     * @param  array  $requestData
     */
    private function checkClearOtherMedia(HasMedia $model, Media $media, string $collectionName, array $requestData)
    {
        $isSingle = isset($requestData['is_single']) ? ((bool) $requestData['is_single']) : null;
        if (is_bool($isSingle) && $isSingle === true) {
            $model->clearMediaCollectionExcept($collectionName, $media);
        }
    }

    /**
     * @param  \Spatie\MediaLibrary\Models\Media  $media
     * @param  string  $conversion
     * @param  array  $allowedProperties
     *
     * @return \StdClass
     */
    private function convertToUploaderImageFormat(Media $media, string $conversion, array $allowedProperties = [])
    {
        $image = new StdClass;

        $image->id = $media->id;
        $image->name = $media->file_name;
        $image->source = $media->getUrl($conversion);

        // issue on background task, so return original immediately
        $image->thumbnail = $media->getUrl();

        $image->deleteUrl = route(config('base.media.route_names.destroy'), $media);
        $image->updatePropertyUrl = route(config('base.media.route_names.update_properties'), $media);
        $image->properties = $this->formatCustomProperties($media->custom_properties, $allowedProperties);

        return $image;
    }

    /**
     * @param  array  $customProperties
     * @param  array  $allowedProperties
     *
     * @return array
     */
    private function formatCustomProperties(array $customProperties, array $allowedProperties)
    {
        $properties = [];

        foreach ($allowedProperties as $property) {
            $properties[$property] = $customProperties[$property] ?? '';
        }

        return $properties;
    }


    /**
     * @param  \Illuminate\Http\Request  $request
     * @param  \Spatie\MediaLibrary\Models\Media  $media
     *
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function updateProperty(Request $request, Media $media)
    {
        $requestData = $this->validate($request, [
            'properties' => 'required',
        ]);

        foreach ($requestData['properties'] as $key => $property) {
            $media->setCustomProperty($key, $property);
        }

        $media->save();

        // Get allowed properties
        $allowedProperties = $media->custom_properties;
        unset($allowedProperties['custom_headers']);
        unset($allowedProperties['generated_conversions']);

        $properties = $this->formatCustomProperties($media->custom_properties, array_keys($allowedProperties));

        app('query.cache')->flush();
        return response()->json(['status' => 'success', 'data' => $properties], 200);
    }
}
