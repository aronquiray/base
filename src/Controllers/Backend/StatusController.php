<?php

namespace HalcyonLaravel\Base\Controllers\Backend;

use Exception;
use Fomvasss\LaravelMetaTags\Facade as MetaTag;
use HalcyonLaravel\Base\Controllers\BaseController;
use HalcyonLaravel\Base\Exceptions\StatusControllerException;
use HalcyonLaravel\Base\Models\Contracts\ModelStatusContract;
use Illuminate\Http\Request;

/**
 * Class StatusController
 *
 * @package HalcyonLaravel\Base\Controllers\Backend
 */
abstract class StatusController extends BaseController
{
    /**
     * @var
     */
    protected $viewPath;

    /**
     * @param  string  $type
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Exception
     */
    public function status(string $type)
    {
        $model = $this->repository()->makeModel();

        if (!$model instanceof ModelStatusContract) {
            throw new Exception('Model must implemented in '.ModelStatusContract::class);
        }

        if (!array_key_exists($type, $model->statuses())) {
            abort(404);
        }

        $statusKey = $model->statusKeyName();

        MetaTag::setTags([
            'title' => $this->getModelName().' '.ucfirst($type).' Management',
        ]);

        return view($model::VIEW_BACKEND_PATH.'.status', compact('type', 'statusKey'));
    }

    /**
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $routeKeyNameValue
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, string $routeKeyNameValue)
    {
        $model = $this->getModel($routeKeyNameValue);
        $statusKey = $model->statusKeyName();
        $status = $request->status ?? null;
        if (is_null($status)) {
            throw new StatusControllerException(403, trans('base::exceptions.status_required'));
        }
        $this->repository()->update([$statusKey => $status], $model->id);
        $redirect = route($this->routePath.'.status', $status);
        $message = trans("base::actions.mark", [
            'name' => $model->base(config('base.responseBaseableName')),
            'status' => $status,
        ]);

        return $this->response('mark', $request->ajax(), $model, $redirect, $message);
    }
}
