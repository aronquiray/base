<?php

namespace HalcyonLaravel\Base\Controllers\Backend;

use HalcyonLaravel\Base\Controllers\BaseController as Controller;
use HalcyonLaravel\Base\Exceptions\StatusControllerException;
use Illuminate\Http\Request;

/**
 * Class StatusController.
 */
abstract class StatusController extends Controller
{
    private $_model;

    public function __construct()
    {
        $m = $this->repository()->model();
        $this->_model = new $m;
    }

    /**
     * @param string $type
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function status(string $type)
    {
        $statusKey = $this->_model->statusKeyName();

        return view("{$this->view_path}.status", compact('type', 'statusKey'));
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @param string $routeKeyNameValue
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, string $routeKeyNameValue)
    {
        $model = $this->getModel($routeKeyNameValue);
        $statusKey = $this->_model->statusKeyName();
        $status = $request->status ?? null;
        if (is_null($status)) {
            throw new StatusControllerException(403, trans('base::exceptions.status_required'));
        }
        $this->repository()->update([$statusKey => $status], $model->id);
        $redirect = route($this->route_path.'.status', $status);
        $message = trans("base::actions.mark", [
            'name' => $model->base(config('base.responseBaseableName')),
            'status' => $status,
        ]);

        return $this->response('mark', $request->ajax(), $model, $redirect, $message);
    }
}
