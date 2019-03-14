<?php

namespace HalcyonLaravel\Base\Controllers\Backend;

use HalcyonLaravel\Base\Controllers\Backend\Contracts\CRUDContract;
use HalcyonLaravel\Base\Controllers\Backend\Traits\CRUDTrait;
use HalcyonLaravel\Base\Controllers\BaseController as Controller;
use Illuminate\Http\Request;
use MetaTag;

/**
 * Class CRUDController.
 */
abstract class CRUDController extends Controller implements CRUDContract
{
    use CRUDTrait;

    /**
     * @var mixed
     */
    protected $viewPath;

    /**
     * @var mixed
     */
    protected $routePath;

    /**
     * CRUDController constructor.
     *
     * @throws \Prettus\Repository\Exceptions\RepositoryException
     */
    public function __construct()
    {
        $model = $this->repository()->makeModel();
        $this->viewPath = $model::VIEW_BACKEND_PATH;
        $this->routePath = $model::ROUTE_ADMIN_PATH;
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Prettus\Repository\Exceptions\RepositoryException
     */
    public function index()
    {
        MetaTag::setTags([
            'title' => $this->getModelName() . ' Management',
        ]);

        $viewPath = $this->viewPath;
        $routePath = $this->routePath;

        return view("{$this->viewPath}.index", compact('viewPath', 'routePath'));
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Prettus\Repository\Exceptions\RepositoryException
     */
    public function create()
    {
        MetaTag::setTags([
            'title' => 'Create ' . $this->getModelName(),
        ]);

        $viewPath = $this->viewPath;
        $routePath = $this->routePath;

        return view("{$this->viewPath}.create", compact('viewPath', 'routePath'));
    }

    /**
     * @param String $routeKeyName
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Prettus\Repository\Exceptions\RepositoryException
     */
    public function edit(String $routeKeyName)
    {
        MetaTag::setTags([
            'title' => 'Edit ' . $this->getModelName(),
        ]);

        $model = $this->getModel($routeKeyName);
        $viewPath = $this->viewPath;
        $routePath = $this->routePath;

        return view("{$this->viewPath}.edit", compact('model', 'viewPath', 'routePath'));
    }

    /**
     * @param String $routeKeyName
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Prettus\Repository\Exceptions\RepositoryException
     */
    public function show(String $routeKeyName)
    {
        $model = $this->getModel($routeKeyName);

        MetaTag::setTags([
            'title' => 'View ' . $this->getModelName(),
        ]);

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
     * @throws \Throwable
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
     * @throws \Throwable
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
     * @throws \Throwable
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
