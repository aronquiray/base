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
    /**
     * @param string $type
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function status(string $type)
    {
        $statusKey = $this->model->statusKeyName();

        return view("{$this->view_path}.status", compact('type', 'statusKey'));
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @param string $routeKeyNameValue
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, string $routeKeyNameValue)
    {
        $model = $this->getModel($routeKeyNameValue);
        $statusKey = $this->model->statusKeyName();
        $status = $request->status ?? null;
        if (is_null($status)) {
            throw StatusControllerException::required();
        }
        $this->repo->update([$statusKey => $status], $model->id);
        $redirect = route($this->route_path.'.status', $status);
        $message = trans("base::actions.mark", [
            'name' => $model->base(config('base.responseBaseableName')),
            'status' => $status,
        ]);

        return $this->response('mark', $request->ajax(), $model, $redirect, $message);
    }
}
