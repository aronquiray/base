<?php

namespace HalcyonLaravel\Base\Controllers\Backend;

use Illuminate\Http\Request;
use HalcyonLaravel\Base\Controllers\BaseController as Controller;

/**
 * Class StatusController.
 */
abstract class StatusController extends Controller
{
    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function status(string $type)
    {
        $statusKey = $this->model->statusKeyName();
        return view("{$this->view_path}.status", compact('type', 'statusKey'));
    }

    /**
     * @param Request $request
     * @param string $routeKeyName
     *
     * @return $response
     */
    public function update(Request $request, string $routeKeyNameValue)
    {
        $model = $this->getModel($routeKeyNameValue);
        $statusKey = $this->model->statusKeyName();
        $status = $request->status ?? null;
        $this->repo->update([ $this->model->statusKeyName() => $status ], $model);
        $redirect = route($this->route_path . '.status', $status) ;
        $message = trans("base::actions.mark.", ['Name' => $model->base(config('base.responseBaseableName')), 'Status' => 'test']);
        return $this->response('mark', $request->ajax(), $model, $redirect);
    }
}
