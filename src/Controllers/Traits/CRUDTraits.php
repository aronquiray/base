<?php

namespace HalcyonLaravel\Base\Controllers\Traits;

trait CRUDTraits
{
    /**
     * Return the model by the given key
     *
     * @param String $key, bool $trash
     *
     * @return Illuminate\Database\Eloquent\Model
     */
    public function getModel($key, $trash = false)
    {
        $model = null;
        $model = $this->model->where($this->model->getRouteKeyName(), $key)->firstOrFail();
        return $model;
    }




    /**
     * Return the response of the request with flash messages
     *
     * @return Illuminate\Database\Eloquent\Model
     */
    public function response($process, bool $isAjax, $model=null)
    {
        $message 	= 'You have successfully ' . $process . 'd the ' . ($model->name?:$model->title) . '.';
        switch ($process) {
            case 'delete':
                $route = route($this->config->route . '.index');
                if (method_exists($this->model, 'bootSoftDeletes')) {
                    $route = route($this->config->route . '.deleted');
                }
                break;
            
            default:
                $route = route($this->config->route . '.show', $model);
                break;
        }

        return $isAjax ? response()->json(['message' => $message, 'link' => $route]) : redirect($route)->withFlashSuccess($message);
    }
}
