<?php

namespace HalcyonLaravel\Base\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Model;

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
     * @param String $key
     * @param bool $trash
     *
     * @return Illuminate\Database\Eloquent\Model
     */
    public function getModel($key, $trash = false)
    {
        $model = $this->model->where($this->model->getRouteKeyName(), $key);
        if ($trash && method_exists($this->model, 'bootSoftDeletes')) { $model->withTrashed(); }
        return $model->firstOrFail();
    }

    /**
     * Return the response of the request with flash messages
     * @param String $process
     * @param bool $isAjax
     * @param Model $model
     * @param String $redirect
     * @param String $message
     *
     * @return Illuminate\Database\Eloquent\Model
     */
    public function response(String $process, bool $isAjax, Model $model, String $redirect = null, String $message = null)
    {
        $message = $message ? $message : __("base::actions.$process", ['Name' => $this->getName($model) ]);
        return $isAjax ? response()->json(['message' => $message, 'link' => $redirect]) : redirect($redirect)->withFlashSuccess($message);
    }
}
