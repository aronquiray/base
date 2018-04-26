<?php

namespace HalcyonLaravel\Base\Controllers\Backend;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Model;
use HalcyonLaravel\Base\Controllers\BaseController as Controller;
use HalcyonLaravel\Base\Models\Traits\ModelTraits;

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
        return view("{$this->view_path}.deleted");
    }

    /**
     * @param Request $request
     * @param String $routeKeyName
     *
     * @return $response
     */
    public function restore(Request $request, String $routeKeyName)
    {
        $model = $this->getModel($routeKeyName, $trash = true);
        $this->repo->restore($model);
        return $this->response('restore', $request->ajax(), $model, route("{$this->route_path}.index"));
    }

    /**
     *
     * @param Request $request, Model $model
     * @return $response
     */
    public function purge(Request $request, String $routeKeyName)
    {
        $model = $this->getModel($routeKeyName, $trash = true);
        $this->repo->purge($model);
        return $this->response('purge', $request->ajax(), $model, route("{$this->route_path}.deleted"));
    }
}
