<?php

namespace HalcyonLaravel\Base\Controllers;

use HalcyonLaravel\Base\Models\Model;

abstract class BaseController extends Controller
{
    /**
     * Model $model
     *
     * @return Model $model
     */
    protected $model;

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
     * @param $key
     * @param bool $trash
     * @param array|null $fields
     * @return mixed
     */
    public function getModel($key, $trash = false, array $fields = null)
    {
        $model = $this->model->where($this->model->getRouteKeyName(), $key);
        if ($trash && method_exists($this->model, 'bootSoftDeletes')) {
            $model->withTrashed();
        }

        if (! is_null($fields)) {
            foreach ($fields as $f => $field) {
                $model = $model->where($f, $field);
            }
        }

        return $model->firstOrFail();
    }

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
        if (! is_null($model) && is_null($message)) {
            $message = trans("base::actions.$process", ['name' => $model->base(config('base.responseBaseableName'))]);
        }

        return $isAjax ? response()->json([
            'message' => $message,
            'link' => $redirect,
        ]) : redirect($redirect)->withFlashSuccess($message);
    }
}
