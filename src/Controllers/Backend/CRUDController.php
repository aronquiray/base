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
        $this->view_path    = $this->model::viewBackendPath;
        $this->route_path   = $this->model::routeAdminPath;
    }


    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        return view("{$this->view_path}.index");
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
        return view("{$this->view_path}.create");
    }

    /**
     * @param String $routeKeyName
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit(String $routeKeyName)
    {
        $model = $this->getModel($routeKeyName);
        return view("{$this->view_path}.edit", compact('model'));
    }

    /**
     * @param String $routeKeyName
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show(String $routeKeyName)
    {
        $model = $this->getModel($routeKeyName);
        return view("{$this->view_path}.show", compact('model'));
    }


    /**
     * @param Request $request
     *
     * @return mixed $response
     */
    public function store(Request $request)
    {
        $this->validate($request, $this->storeRules($request));
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
        $this->validate($request, $this->updateRules($request, $model));
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
