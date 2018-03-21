<?php

namespace HalcyonLaravel\Base\Controllers\Backend;

use Illuminate\Http\Request;
use HalcyonLaravel\Base\Controllers\BaseController as Controller;
use HalcyonLaravel\Base\Controllers\Backend\Contracts\StatusContract;

/**
 * Class StatusController.
 */
abstract class StatusController extends Controller implements StatusContract
{
    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function inactive()
    {
        return view("{$this->view_path}.disabled");
    }

    /**
     * @param Request $request
     * @param String $routeKeyName
     *
     * @return $response
     */
    public function __invoke(Request $request, String $routeKeyNameValue)
    {
        $model = $this->getModel($routeKeyNameValue);

        // reverse status value
        $newStatus = $this->statusIsActive($model) ?  $this->statusInactiveLabel() :  $this->statusActiveLabel();

        $this->repo->update([
            $this->statusKeyName() => $newStatus,
        ], $model);

        $redirect = route($this->route_path . '.' . ($this->statusIsActive($model) ? 'index' : 'disabled')) ;

        $message = trans("base::actions.mark.", ['Name' => $model->base(config('base.responseBaseableName')), 'status' => $newStatus]);
        return $this->response('mark', $request->ajax(), $model, $redirect);
    }
}
