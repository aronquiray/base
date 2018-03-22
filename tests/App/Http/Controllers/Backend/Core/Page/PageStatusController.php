<?php

namespace App\Http\Controllers\Backend\Core\Page;

use HalcyonLaravel\Base\Controllers\Backend\StatusController as Controller;
use Illuminate\Http\Request;
use HalcyonLaravel\Base\Repository\BaseRepository as Repository;

use App\Models\Core\Page as Model;

/**
 * Class ContentStatusController.
 */
class PageStatusController extends Controller
{
    public function __construct(Model $model)
    {
        $this->repo         = new Repository($model);
        $this->model        = $model;
        $this->route_path   = Model::routeAdminPath;
        $this->view_path    = Model::viewBackendPath;
        // $this->middleware('permission:page inactive', ['only' => ['inactive']]);
        // $this->middleware('permission:page change status', ['only' => ['mark']]);
    }

    /**
     * Return the links related to this model.
     *
     * @return array
     */
    public function statusKeyName(): string
    {
        return 'status';
    }

    /**
     * Return the bool if status is Active.
     *
     * @return array
     */
    public function statusIsActive($model) : bool
    {
        return $model->{$this->statusKeyName()} == $this->statusActiveLabel();
    }

    /**
     * Return the string label if model is Active.
     *
     * @return array
     */
    public function statusActiveLabel() : string
    {
        return 'enable';
    }

    /**
     * Return the string label if model is Inactive.
     *
     * @return array
     */
    public function statusInactiveLabel() : string
    {
        return 'disabled';
    }

    /**
     * @param Request $request
     * @param String $routeKeyName
     *
     * @return $response
     */
    public function modelIsActive($model) : bool
    {
        return true;
    }
}
