<?php

namespace HalcyonLaravel\Base\Controllers;

use HalcyonLaravel\Base\Criterion\AutoOrderBootedByCriteria;
use HalcyonLaravel\Base\Models\Model;
use HalcyonLaravel\Base\Repository\BaseRepository;

abstract class BaseController extends Controller
{
    protected $repository;
    /**
     * View Path
     *
     * @return String
     */
    protected $view_path;
    /**
     * Route Path
     *
     * @return String
     */
    protected $route_path;

    /**
     * Return the model by the given key
     *
     * @param string $key
     * @param bool $trash
     * @param array|null $customWhere
     * @return mixed
     */
    public function getModel(string $key, bool $trash = false, array $customWhere = null)
    {
        $repo = $this->repository();
        $repo->popCriteria(new AutoOrderBootedByCriteria);

        $modelClass = resolve($repo->model());

        $where = [
            $modelClass->getRouteKeyName() => $key,
        ];

        if (!is_null($customWhere)) {
            $where = array_merge($where, $customWhere);
        }

        if ($trash && method_exists($modelClass, 'bootSoftDeletes')) {
            $model = $repo->scopeQuery(function ($query) {
                return $query->withTrashed();
            })->findWhere($where)->first();
        } else {
            $model = $repo->findWhere($where)->first();
        }
        if (is_null($model)) {
            abort(404);
        }

        return $model;
    }

    /**
     * @return BaseRepository
     */
    abstract public function repository(): BaseRepository;

    /**
     * Return the response of the request with flash messages
     *
     * @param String $process
     * @param bool $isAjax
     * @param \HalcyonLaravel\Base\Models\Model|null $model
     * @param String|null $redirect
     * @param String|null $message
     * @return \Illuminate\Http\JsonResponse
     */
    public function response(
        String $process,
        bool $isAjax,
        Model $model = null,
        String $redirect = null,
        String $message = null
    ) {
        if (!is_null($model) && is_null($message)) {
            $message = trans("base::actions.$process", ['name' => $model->base(config('base.responseBaseableName'))]);
        }

        return $isAjax ? response()->json([
            'message' => $message,
            'link' => $redirect,
        ]) : redirect($redirect)->withFlashSuccess($message);
    }
}
