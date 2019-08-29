<?php

namespace HalcyonLaravel\Base\Http\Controllers;

use HalcyonLaravel\Base\Criterion\Eloquent\ThisEqualThatCriteria;
use HalcyonLaravel\Base\Criterion\Eloquent\ThisScopeCriteria;
use HalcyonLaravel\Base\Criterion\Eloquent\WithTrashCriteria;
use HalcyonLaravel\Base\Models\Model;
use HalcyonLaravel\Base\Repository\BaseRepositoryInterface;
use Illuminate\Database\Eloquent\ModelNotFoundException;

/**
 * Class BaseController
 *
 * @package HalcyonLaravel\Base\Controllers
 */
abstract class BaseController extends Controller
{
    /**
     * View Path
     *
     * @return String
     */
    protected $viewPath;
    /**
     * Route Path
     *
     * @return String
     */
    protected $routePath;

    /**
     * @param  string  $key
     * @param  bool  $trash
     * @param  array|null  $customWhere
     * @param  string|null  $queryScope
     *
     * @return mixed
     */
    public function getModel(string $key, bool $trash = false, array $customWhere = null, string $queryScope = null)
    {
        $repo = $this->repository();

        $modelClass = $repo->makeModel();

        $where = [
            $modelClass->getRouteKeyName() => $key,
        ];

        if (!is_null($customWhere)) {
            $where = array_merge($where, $customWhere);
        }

        if (!is_null($queryScope)) {
            $repo->pushCriteria(new ThisScopeCriteria($queryScope));
        }

        if ($trash && method_exists($modelClass, 'bootSoftDeletes')) {
//            $model = $repo->scopeQuery(function ($query) {
//                return $query->withTrashed();
//            })->findWhere($where)->first();
            $repo->pushCriteria(new WithTrashCriteria);
        }
//        else {
//            $model = $repo->findWhere($where)->first();
        foreach ($where as $field => $value) {
            $repo->pushCriteria(new ThisEqualThatCriteria($field, $value));
        }
//        }

        $model = $repo->all()->first();

        if (is_null($model)) {
            throw new ModelNotFoundException($model);
        }

        return $model;
    }

    /**
     * @return \HalcyonLaravel\Base\Repository\BaseRepositoryInterface
     */
    abstract public function repository(): BaseRepositoryInterface;

    /**
     * Return the response of the request with flash messages
     *
     * @param  String  $process
     * @param  bool  $isAjax
     * @param  \HalcyonLaravel\Base\Models\Model|null  $model
     * @param  String|null  $redirect
     * @param  String|null  $message
     * @param  int|null  $statusCode
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function response(
        String $process,
        bool $isAjax,
        Model $model = null,
        String $redirect = null,
        String $message = null,
        int $statusCode = null
    ) {
        if (!is_null($model) && is_null($message)) {
            $message = trans("base::actions.$process", ['name' => $model->base(config('base.responseBaseableName'))]);
        }

        return $isAjax
            ? response()->json([
                'message' => $message,
                'link' => $redirect,
            ], $statusCode ?: 200)
            : redirect($redirect, $statusCode ?: 302)->withFlashSuccess($message);
    }

    /**
     * @return string
     */
    protected function getModelName(): string
    {
        return ucwords($this->repository()->makeModel()::MODULE_NAME);
    }
}
