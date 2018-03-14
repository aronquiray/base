<?php

namespace HalcyonLaravel\Base\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use HalcyonLaravel\Base\Controllers\Traits\CRUDTraits;

/**
 * Class CRUDController.
 */
abstract class CRUDController extends Controller
{
    use CRUDTraits;

    public $config;

    abstract public function generateStubStore(Request $request) :array;
    abstract public function generateStubUpdate(Request $request) :array;
    abstract public function storeRules():array;
    abstract public function updateRules($model):array;

    public function index()
    {
        return view($this->config->view . '.index');
    }

    public function create()
    {
        return view($this->config->view . '.create');
    }

    public function edit($slug)
    {
        $model = $this->getModel($slug);
        return view($this->config->view . '.edit', compact('model'));
    }

    public function show($slug)
    {
        $model = $this->getModel($slug);
        return view($this->config->view . '.show', compact('model'));
    }

    public function store(Request $request)
    {
        $this->validate($request, $this->storeRules());
        $requestArray = $this->generateStubStore($request);
        $model = $this->repo->store($requestArray);
        return $this->response('store', $request->ajax(), $model);
    }

    public function update(Request $request, $slug)
    {
        $model = $this->getModel($slug);
        $this->validate($request, $this->updateRules($model));
        $requestArray = $this->generateStubUpdate($request);
        $model = $this->repo->store($requestArray, $model);
        return $this->response('update', $request->ajax(), $model);
    }

    public function destroy(Request $request, $slug)
    {
        $model = $this->getModel($slug);
        $this->repo->destroy($model);
        return $this->response('delete', $request->ajax(), $model);
    }
}
