<?php

namespace App\Http\Controllers\Backend\Core\Page;

use App\Models\Core\PageSoftDelete as Model;
use App\Repositories\PageDeleteRepository;
use HalcyonLaravel\Base\Controllers\Backend\DeletedController as Controller;
use HalcyonLaravel\Base\Repository\BaseRepository;

/**
 * Class PagesController.
 */
class PagesSoftDeleteController extends Controller
{
    protected $pageDeleteRepository;

    /**
     * PagesSoftDeleteController constructor.
     *
     * @param \App\Repositories\PageDeleteRepository $pageDeleteRepository
     */
    public function __construct(PageDeleteRepository $pageDeleteRepository)
    {
        $this->pageDeleteRepository = $pageDeleteRepository;
        $this->route_path = Model::ROUTE_ADMIN_PATH;
        $this->view_path = 'backend.core.page';// for testing only
        // $this->view_path    = Model::viewBackendPath;

        // parent::__construct();
    }

    public function repository(): BaseRepository
    {
        return $this->pageDeleteRepository;
    }
}
