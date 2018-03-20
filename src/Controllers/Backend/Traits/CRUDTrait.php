<?php

namespace HalcyonLaravel\Base\Controllers\Backend\Traits;

use Illuminate\Http\Request;

trait CRUDTrait
{
    /**
     * @param Request $request
     * @param mixed $args
     * @param Model $model | nullable
     *
     * @return void
     */
    protected function _redirectAfterAction(String $submission = null, $args) : String
    {
        if ($submission && strpos($submission, 'http') !== false) {
            return $submission;
        }
        $submission = $submission ?? 'show';
        return route("$this->route_path.$submission", $args);
    }
}
