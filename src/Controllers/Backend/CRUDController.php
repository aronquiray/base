<?php

namespace HalcyonLaravel\Base\Controllers\Backend;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Model;
use HalcyonLaravel\Base\Controllers\BaseController as Controller;
use HalcyonLaravel\Base\Controllers\Backend\Contract\CRUDContract;

/**
 * Class CRUDController.
 */
abstract class CRUDController extends Controller implements CRUDContract
{
    /**
     * CRUDController Constructor
     */
    public function __construct()
    {
        $this->view_path    = $this->model->view_backend_path;
        $this->route_path   = $this->model->route_admin_path;
    }


    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        return view("$this->view_path.index");
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
        return view("$this->view_path.create");
    }

    /**
     * @param String $routeKeyName
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit(String $routeKeyName)
    {
        $model = $this->getModel($routeKeyName);
        return view("$this->view_path.edit", compact('model'));
    }

    /**
     * @param String $routeKeyName
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show(String $routeKeyName)
    {
        $model = $this->getModel($routeKeyName);
        return view("$this->view_path.show", compact('model'));
    }


    /**
     * @param Request $request
     *
     * @return mixed $response
     */
    public function store(Request $request)
    {
        $this->validate($request, $this->storeRules());
        $data = $this->generateStub($request);
        $model = $this->repo->store($data);
        return $this->response('store', $request->ajax(), $model, route("$this->route_path.show", $model));
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
        $this->validate($request, $this->updateRules($model));
        $data = $this->generateStub($request, $model);
        $model = $this->repo->update($data, $model);
        return $this->response('update', $request->ajax(), $model, route("$this->route_path.show", $model));
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
        $redirect = route("$this->route_path." . (method_exists($this->model, 'bootSoftDeletes') ? 'deleted' : 'index'));
        return $this->response('delete', $request->ajax(), $model, $redirect);
    }
}
