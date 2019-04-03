<?php

namespace HalcyonLaravel\Base\Tests\Http\Controllers\Backend\Core\Page;

use HalcyonLaravel\Base\Controllers\Backend\DeletedController as Controller;
use HalcyonLaravel\Base\Repository\BaseRepositoryInterface;
use HalcyonLaravel\Base\Tests\Models\Core\PageSoftDelete as Model;
use HalcyonLaravel\Base\Tests\Repositories\PageDeleteRepository;

/**
 * Class PagesController.
 */
class PagesSoftDeleteController extends Controller
{
    protected $pageDeleteRepository;

    /**
     * PagesSoftDeleteController constructor.
     *
     * @param  \HalcyonLaravel\Base\Tests\Repositories\PageDeleteRepository  $pageDeleteRepository
     */
    public function __construct(PageDeleteRepository $pageDeleteRepository)
    {
        $this->pageDeleteRepository = $pageDeleteRepository;
        $this->routePath = Model::ROUTE_ADMIN_PATH;
        $this->viewPath = 'backend.core.page';// for testing only
        // $this->view_path    = Model::viewBackendPath;

        // parent::__construct();
    }

    public function repository(): BaseRepositoryInterface
    {
        return $this->pageDeleteRepository;
    }
}
