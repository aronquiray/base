<?php

namespace HalcyonLaravel\Base\Controllers\Backend\Contracts;

use Illuminate\Http\Request;
use HalcyonLaravel\Base\BasableOptions;

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
    public function crudRules(Request $request, $model = null) : BasableOptions;
}
