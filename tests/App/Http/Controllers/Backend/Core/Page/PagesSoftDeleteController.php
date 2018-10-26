<?php

namespace App\Http\Controllers\Backend\Core\Page;

use App\Models\Core\PageSoftDelete as Model;
use HalcyonLaravel\Base\Controllers\Backend\DeletedController as Controller;
use HalcyonLaravel\Base\Repository\BaseRepository as Repository;

/**
 * Class PagesController.
 */
class PagesSoftDeleteController extends Controller
{
    /**
     * PagesController Constructor
     */
    public function __construct(Model $model)
    {
        $this->model = $model;
        $this->repo = new Repository($model);
        $this->route_path = Model::ROUTE_ADMIN_PATH;
        $this->view_path = 'backend.core.page';// for testing only
        // $this->view_path    = Model::viewBackendPath;

        // parent::__construct();
    }

    /**
     * Specify Model class name.
     *
     * @return mixed
     */
    public function model()
    {
        return Model::class;
    }
}
