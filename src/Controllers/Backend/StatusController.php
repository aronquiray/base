<?php

namespace HalcyonLaravel\Base\Controllers\Backend;

use HalcyonLaravel\Base\Controllers\BaseController;
use HalcyonLaravel\Base\Exceptions\StatusControllerException;
use Illuminate\Http\Request;

/**
 * Class StatusController.
 */
abstract class StatusController extends BaseController
{
    /**
     * @param string $type
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function status(string $type)
    {
        $model = resolve($this->repository()->model());
        $statusKey = $model->statusKeyName();

        return view("{$this->viewPath}.status", compact('type', 'statusKey'));
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @param string                   $routeKeyNameValue
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
        $redirect = route($this->routePath . '.status', $status);
        $message = trans("base::actions.mark", [
            'name' => $model->base(config('base.responseBaseableName')),
            'status' => $status,
        ]);

        return $this->response('mark', $request->ajax(), $model, $redirect, $message);
    }
}
