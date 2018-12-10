<?php

namespace HalcyonLaravel\Base\Controllers\Backend\Contracts;

use HalcyonLaravel\Base\BaseableOptions;
use Illuminate\Database\Eloquent\Model as IlluminateModel;
use Illuminate\Http\Request;

interface CRUDContract
{
    /**
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function generateStub(Request $request): array;

    /**
     * Validate input on store and update
     *
     * @param \Illuminate\Http\Request $request
     * @param \Illuminate\Database\Eloquent\Model|null $model
     * @return \HalcyonLaravel\Base\BaseableOptions
     */
    public function crudRules(Request $request, IlluminateModel $model = null): BaseableOptions;
}
