<?php

namespace HalcyonLaravel\Base\Controllers\Backend\Contracts;

use Illuminate\Http\Request;
use HalcyonLaravel\Base\BasableOptions;
use Illuminate\Database\Eloquent\Model as IlluminateModel;

interface CRUDContract
{
    /**
     * @param Request $request
     * @param Model $model | nullable
     *
     * @return array
     */
    public function generateStub(Request $request) : array;

    /**
     * Validate input on store and update
     *
     * @return array
     */
    public function crudRules(Request $request, IlluminateModel $model = null) : BasableOptions;
}
