<?php

namespace HalcyonLaravel\Base\Controllers\Backend;

use HalcyonLaravel\Base\Controllers\Backend\Contracts\CRUDContract;
use HalcyonLaravel\Base\Controllers\Backend\Traits\CRUDTrait;
use HalcyonLaravel\Base\Controllers\BaseController as Controller;
use Illuminate\Http\Request;

/**
 * Class CRUDController.
 */
abstract class CRUDController extends Controller implements CRUDContract
{
    use CRUDTrait;

    protected $viewPath;
    protected $routePath;

    /**
     * CRUDController constructor.
     */
    public function __construct()
    {
        $model = resolve($this->repository()->model());
        $this->viewPath = $model::VIEW_BACKEND_PATH;
        $this->routePath = $model::ROUTE_ADMIN_PATH;
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $viewPath = $this->viewPath;
        $routePath = $this->routePath;

        return view("{$this->viewPath}.index", compact('viewPath', 'routePath'));
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
        $viewPath = $this->viewPath;
        $routePath = $this->routePath;

        return view("{$this->viewPath}.create", compact('viewPath', 'routePath'));
    }

    /**
     * @param String $routeKeyName
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit(String $routeKeyName)
    {
        $model = $this->getModel($routeKeyName);
        $viewPath = $this->viewPath;
        $routePath = $this->routePath;

        return view("{$this->viewPath}.edit", compact('model', 'viewPath', 'routePath'));
    }

    /**
     * @param String $routeKeyName
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show(String $routeKeyName)
    {
        $model = $this->getModel($routeKeyName);
        $viewPath = $this->viewPath;
        $routePath = $this->routePath;

        return view("{$this->viewPath}.show", compact('model', 'viewPath', 'routePath'));
    }

    /**
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     * @throws \Prettus\Validator\Exceptions\ValidatorException
     */
    public function store(Request $request)
    {
        $baseableOptions = $this->crudRules($request);
        $this->validate($request, $baseableOptions->storeRules, $baseableOptions->storeRuleMessages);

        $data = $this->generateStub($request);
        $model = $this->repository()->create($data);

        return $this->response('store', $request->ajax(), $model,
            $this->_redirectAfterAction($request->_submission, $model));
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @param String                   $routeKeyName
     *
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     * @throws \Prettus\Validator\Exceptions\ValidatorException
     */
    public function update(Request $request, String $routeKeyName)
    {
        $model = $this->getModel($routeKeyName);

        $baseableOptions = $this->crudRules($request, $model);
        $this->validate($request, $baseableOptions->updateRules, $baseableOptions->updateRuleMessages);

        $data = $this->generateStub($request, $model);
        $model = $this->repository()->update($data, $model->id);

        return $this->response('update', $request->ajax(), $model,
            $this->_redirectAfterAction($request->_submission, $model));
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @param                          $slug
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Request $request, $slug)
    {
        $model = $this->getModel($slug);
        $this->repository()->delete($model->id);
        $redirect = route($this->routePath . '.' . (method_exists($this->repository()->model(),
                'bootSoftDeletes') ? 'deleted' : 'index'));

        return $this->response('destroy', $request->ajax(), $model, $redirect);
    }
}
