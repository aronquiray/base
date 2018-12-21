<?php

namespace HalcyonLaravel\Base\Controllers\Backend;

use HalcyonLaravel\Base\Controllers\BaseController;
use Illuminate\Http\Request;

/**
 * Class DeletedController.
 */
abstract class DeletedController extends BaseController
{

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function deleted()
    {
        $viewPath = $this->viewPath;
        $routePath = $this->routePath;

        return view("{$this->viewPath}.deleted", compact('viewPath', 'routePath'));
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @param String                   $routeKeyName
     *
     * @return \Illuminate\Http\JsonResponse
     * @throws \Prettus\Repository\Exceptions\RepositoryException
     */
    public function restore(Request $request, String $routeKeyName)
    {
        $model = $this->getModel($routeKeyName, $trash = true);
        $this->repository()->restore($model->id);

        return $this->response('restore', $request->ajax(), $model, route("{$this->routePath}.index"));
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @param String                   $routeKeyName
     *
     * @return \Illuminate\Http\JsonResponse
     * @throws \Prettus\Repository\Exceptions\RepositoryException
     */
    public function purge(Request $request, String $routeKeyName)
    {
        $model = $this->getModel($routeKeyName, $trash = true);
        $this->repository()->purge($model->id);

        return $this->response('purge', $request->ajax(), $model, route("{$this->routePath}.deleted"));
    }
}
