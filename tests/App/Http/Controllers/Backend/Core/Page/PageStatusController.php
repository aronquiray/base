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
        $this->route_path   = Model::ROUTE_ADMIN_PATH;
        $this->view_path    = Model::VIEW_BACKEND_PATH;
        // $this->middleware('permission:page inactive', ['only' => ['inactive']]);
        // $this->middleware('permission:page change status', ['only' => ['mark']]);
    }
}
