<?php

namespace HalcyonLaravel\Base\Controllers\Backend\Contracts;

use HalcyonLaravel\Base\BaseableOptions;
use Illuminate\Database\Eloquent\Model as IlluminateModel;
use Illuminate\Http\Request;

/**
 * Interface CRUDContract
 *
 * @package HalcyonLaravel\Base\Controllers\Backend\Contracts
 */
interface CRUDContract
{
    /**
     * @param \Illuminate\Http\Request            $request
     * @param \Illuminate\Database\Eloquent\Model $model
     *
     * @return array
     */
    public function generateStub(Request $request, IlluminateModel $model = null): array;

    /**
     * Validate input on store and update
     *
     * @param \Illuminate\Http\Request                 $request
     * @param \Illuminate\Database\Eloquent\Model|null $model
     *
     * @return \HalcyonLaravel\Base\BaseableOptions
     */
    public function crudRules(Request $request, IlluminateModel $model = null): BaseableOptions;
}
