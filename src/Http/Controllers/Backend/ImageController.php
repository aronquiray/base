<?php

namespace HalcyonLaravel\Base\Http\Controllers\Backend;

use HalcyonLaravel\Base\Http\Controllers\Controller;
use HalcyonLaravel\Base\Models\Contracts\BaseModelInterface;
use HalcyonLaravel\Base\Models\Model as HalcyonBaseModel;
use Illuminate\Database\Eloquent\Model as IlluminateModel;
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
abstract class ImageController extends Controller
{
    /**
     * @param  \Illuminate\Http\Request  $request
     *
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function order(Request $request)
    {
        $this->validate($request, [
            'orderedIds.*' => 'required|integer'
        ]);

        Media::setNewOrder($request->orderedIds);

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
        $model = $media->model()->first();
        $modelClassName = get_class($model);

        if ($model->media->count() > 1) {
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

        $collectionName = 'all collection names.';
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
            'message' => "Image required in [{$modelClassName}] with {$collectionName}",
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

    abstract protected function noneRequiredModels(): array;

    /**
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $routeKeyValue
     *
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function upload(Request $request, string $routeKeyValue)
    {
        $this->validate($request, [
            'model' => 'required|in:'.$this->allowedModels(),
            'collection' => 'nullable|string',
            'conversion' => 'nullable|string',
        ]);

        $model = $this->getModel($request->model, $routeKeyValue);

        $collectionName = $request->collection ?? $model->collection_name ?? 'images';
        $conversion = $request->conversion ?? '';

        $rules = isset($this->validations()[$this->models()[$request->model]]) ? $this->validations()[$this->models()[$request->model]] : [];

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

        $this->validate($request, [
            'image' => $rules ?: 'required|image',
        ]);

        abort_if(is_null($collectionName), 422, 'No collection name specified, aborted.');

        if ($model instanceof BaseModelInterface) {
            $fileName = $model->base();
        } else {
            $fileName = $this->getFileName($request->image->getClientOriginalName());
        }

        $latestImage = $model->addMedia($request->image)
            ->withCustomProperties([
                'attributes' => [
                    'alt' => $fileName,
                    'title' => $fileName,
                ]
            ])
            ->toMediaCollection($collectionName);

        $image = $this->convertToUploaderImageFormat($latestImage, $conversion,
            defined($this->models()[$request->model].'::MEDIA_LIBRARY_CUSTOM_PROPERTIES')
                ? array_merge(['attributes'], $model::MEDIA_LIBRARY_CUSTOM_PROPERTIES)
                : ['attributes']
        );

        // update og_image field if model if MetaTag
        $this->forMeta($model);

        return response()->json([
            'status' => 'success',
            'data' => $image,
            'message' => 'Success upload for ['.get_class($model).']',
        ], 200);
    }

    /**
     * @return string
     */
    private function allowedModels(): string
    {
        return implode(',', array_keys($this->models()));
    }

    abstract protected function models(): array;

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

    abstract protected function validations(): array;

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
     * @param  \Spatie\MediaLibrary\Models\Media  $media
     * @param  string  $conversion
     * @param  array  $allowedProperties
     *
     * @return \StdClass
     */
    private function convertToUploaderImageFormat(Media $media, string $conversion, array $allowedProperties = [])
    {
        $image = new StdClass;

        $image->name = $media->file_name;
        $image->source = $media->getUrl($conversion);

        // issue on background task, so return original immediately
        $image->thumbnail = $media->getUrl();

        $image->deleteUrl = route(app('config')->get('base.media.route_names.destroy'), $media);
        $image->updatePropertyUrl = route(app('config')->get('base.media.route_names.update_properties'), $media);
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
     * @param $model
     */
    private function forMeta(IlluminateModel $model)
    {
        $metaTagModel = app(app('config')->get('base.models.metaTag'));
        if ($model instanceof $metaTagModel) {
            $model->update([
                'og_image' => $model->getFirstMediaUrl('images', 'og_image'),
            ]);
        }
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
        $this->validate($request, [
            'properties' => 'required',
        ]);

        foreach ($request->properties as $key => $property) {
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
