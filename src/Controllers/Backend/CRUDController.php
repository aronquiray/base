<?php

namespace HalcyonLaravel\Base\Controllers\Backend;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Model;
use HalcyonLaravel\Base\Controllers\BaseController as Controller;
use HalcyonLaravel\Base\Controllers\Backend\Contracts\CRUDContract;
use HalcyonLaravel\Base\Controllers\Backend\Traits\CRUDTrait;

/**
 * Class CRUDController.
 */
abstract class CRUDController extends Controller implements CRUDContract
{
    use CRUDTrait;
    /**
     * CRUDController Constructor
     */
    public function __construct()
    {
        $this->view_path    = $this->model::VIEW_BACKEND_PATH;
        $this->route_path   = $this->model::ROUTE_ADMIN_PATH;
    }


    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $viewPath =  $this->view_path;
        $routePath =  $this->route_path;
        return view("{$this->view_path}.index", compact('viewPath', 'routePath'));
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
        $viewPath =  $this->view_path;
        $routePath =  $this->route_path;
        return view("{$this->view_path}.create", compact('viewPath', 'routePath'));
    }

    /**
     * @param String $routeKeyName
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit(String $routeKeyName)
    {
        $model = $this->getModel($routeKeyName);
        $viewPath =  $this->view_path;
        $routePath =  $this->route_path;
        return view("{$this->view_path}.edit", compact('model', 'viewPath', 'routePath'));
    }

    /**
     * @param String $routeKeyName
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show(String $routeKeyName)
    {
        $model = $this->getModel($routeKeyName);
        $viewPath =  $this->view_path;
        $routePath =  $this->route_path;
        return view("{$this->view_path}.show", compact('model', 'viewPath', 'routePath'));
    }


    /**
     * @param Request $request
     *
     * @return mixed $response
     */
    public function store(Request $request)
    {
        $basableOptions = $this->crudRules($request);
        $this->validate($request, $basableOptions->storeRules, $basableOptions->storeRuleMessages);

        $data = $this->generateStub($request);
        $model = $this->repo->store($data);
        return $this->response('store', $request->ajax(), $model, $this->_redirectAfterAction($request->_submission, $model));
    }

    /**
     * @param Request $request
     * @param String $routeKeyName
     *
     * @return mixed $response
     */
    public function update(Request $request, String $routeKeyName)
    {
        $model = $this->getModel($routeKeyName);

        $basableOptions = $this->crudRules($request, $model);
        $this->validate($request, $basableOptions->updateRules, $basableOptions->updateRuleMessages);

        $data = $this->generateStub($request, $model);
        $model = $this->repo->update($data, $model);
        return $this->response('update', $request->ajax(), $model, $this->_redirectAfterAction($request->_submission, $model));
    }

    /**
     * @param Request $request
     * @param String $routeKeyName
     *
     * @return mixed $response
     */
    public function destroy(Request $request, $slug)
    {
        $model = $this->getModel($slug);
        $this->repo->destroy($model);
        $redirect = route($this->route_path . '.' . (method_exists($this->model, 'bootSoftDeletes') ? 'deleted' : 'index'));
        return $this->response('destroy', $request->ajax(), $model, $redirect);
    }
}
