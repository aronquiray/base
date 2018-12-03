<?php

namespace HalcyonLaravel\Base\Controllers\Backend;

use HalcyonLaravel\Base\Controllers\BaseController as Controller;
use HalcyonLaravel\Base\Models\Traits\ModelTraits;
use Illuminate\Http\Request;

/**
 * Class DeletedController.
 */
abstract class DeletedController extends Controller
{
    use ModelTraits;

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function deleted()
    {
        $viewPath = $this->view_path;
        $routePath = $this->route_path;

        return view("{$this->view_path}.deleted", compact('viewPath', 'routePath'));
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @param String $routeKeyName
     * @return \Illuminate\Http\JsonResponse
     */
    public function restore(Request $request, String $routeKeyName)
    {
        $model = $this->getModel($routeKeyName, $trash = true);
        $this->repository()->restore($model);

        return $this->response('restore', $request->ajax(), $model, route("{$this->route_path}.index"));
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @param String $routeKeyName
     * @return \Illuminate\Http\JsonResponse
     */
    public function purge(Request $request, String $routeKeyName)
    {
        $model = $this->getModel($routeKeyName, $trash = true);
        $this->repository()->purge($model);

        return $this->response('purge', $request->ajax(), $model, route("{$this->route_path}.deleted"));
    }
}
