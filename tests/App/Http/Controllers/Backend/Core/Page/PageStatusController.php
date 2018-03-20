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
        $this->repo     = new Repository($model);
        $this->model    = $model;
        $this->route    = 'admin.page';
        $this->view    	= 'backend.page';
        // $this->middleware('permission:page inactive', ['only' => ['inactive']]);
        // $this->middleware('permission:page change status', ['only' => ['mark']]);
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
