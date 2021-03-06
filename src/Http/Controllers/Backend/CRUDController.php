<?php

namespace HalcyonLaravel\Base\Http\Controllers\Backend;

use Fomvasss\LaravelMetaTags\Facade as MetaTag;
use HalcyonLaravel\Base\Http\Controllers\Backend\Contracts\CRUDContract;
use HalcyonLaravel\Base\Http\Controllers\Backend\Traits\CRUDTrait;
use HalcyonLaravel\Base\Http\Controllers\BaseController;
use Illuminate\Http\Request;

/**
 * Class CRUDController
 *
 * @package HalcyonLaravel\Base\Controllers\Backend
 */
abstract class CRUDController extends BaseController implements CRUDContract
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
     * @var array
     */
    protected $checkChildRelationOnDestroy = [

    ];


    /**
     * CRUDController constructor.
     */
    public function __construct()
    {
        $model = $this->repository()->makeModel();
        $this->viewPath = $model::VIEW_BACKEND_PATH;
        $this->routePath = $model::ROUTE_ADMIN_PATH;
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        MetaTag::setTags([
            'title' => $this->getModelName().' Management',
        ]);

        $viewPath = $this->viewPath;
        $routePath = $this->routePath;

        return view("{$this->viewPath}.index", compact('viewPath', 'routePath'));
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
        MetaTag::setTags([
            'title' => 'Create '.$this->getModelName(),
        ]);

        $viewPath = $this->viewPath;
        $routePath = $this->routePath;

        return view("{$this->viewPath}.create", compact('viewPath', 'routePath'));
    }

    /**
     * @param  string  $routeKeyName
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit(string $routeKeyName)
    {
        MetaTag::setTags([
            'title' => 'Edit '.$this->getModelName(),
        ]);

        $model = $this->getModel($routeKeyName);
        $viewPath = $this->viewPath;
        $routePath = $this->routePath;

        return view("{$this->viewPath}.edit", compact('model', 'viewPath', 'routePath'));
    }

    /**
     * @param  string  $routeKeyName
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show(string $routeKeyName)
    {
        $model = $this->getModel($routeKeyName);

        MetaTag::setTags([
            'title' => 'View '.$this->getModelName(),
        ]);

        $viewPath = $this->viewPath;
        $routePath = $this->routePath;

        return view("{$this->viewPath}.show", compact('model', 'viewPath', 'routePath'));
    }

    /**
     * @param  \Illuminate\Http\Request  $request
     *
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request)
    {
        $baseableOptions = $this->crudRules($request);
        $this->validate(
            $request,
            $baseableOptions->storeRules,
            $baseableOptions->storeRuleMessages,
            $baseableOptions->storeCustomAttributes
        );

        $data = $this->generateStub($request);
        $model = $this->repository()->create($data);

        return $this->response(
            'store',
            $request->ajax(),
            $model,
            $this->_redirectAfterAction($request->_submission, $model)
        );
    }

    /**
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $routeKeyName
     *
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function update(Request $request, string $routeKeyName)
    {
        $model = $this->getModel($routeKeyName);

        $baseableOptions = $this->crudRules($request, $model);
        $this->validate(
            $request,
            $baseableOptions->updateRules,
            $baseableOptions->updateRuleMessages,
            $baseableOptions->updateCustomAttributes
        );

        $data = $this->generateStub($request, $model);
        $model = $this->repository()->update($data, $model->id);

        return $this->response('update', $request->ajax(), $model,
            $this->_redirectAfterAction($request->_submission, $model));
    }

    /**
     * @param  \Illuminate\Http\Request  $request
     * @param $slug
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Request $request, $slug)
    {
        $model = $this->getModel($slug);

        foreach ($this->checkChildRelationOnDestroy as $item) {
            if ($model->{$item}()->count() > 0) {
                return $this->response(
                    'destroy',
                    $request->ajax(),
                    $model,
                    route($this->routePath.'.index'),
                    trans('base::exceptions.not_deleted_has_child_data', [
                        'name' => $model->base(config('base.responseBaseableName')),
                        'child_relation' => $item,
                    ]),
                    422
                );
            }
        }

        $this->repository()->delete($model->id);
        $redirect = route($this->routePath.'.'.(method_exists($this->repository()->model(),
                'bootSoftDeletes') ? 'deleted' : 'index'));

        return $this->response('destroy', $request->ajax(), $model, $redirect);
    }
}
