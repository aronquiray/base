<?php

namespace HalcyonLaravel\Base\Tests\Http\Controllers\Backend\Core\Page;

use HalcyonLaravel\Base\Controllers\Backend\StatusController as Controller;
use HalcyonLaravel\Base\Repository\BaseRepository;
use HalcyonLaravel\Base\Tests\Models\Core\Page as Model;
use HalcyonLaravel\Base\Tests\Repositories\PageRepository;

/**
 * Class ContentStatusController.
 */
class PageStatusController extends Controller
{
    protected $pageRepository;

    public function __construct(PageRepository $pageRepository)
    {
        $this->pageRepository = $pageRepository;

        $this->routePath = Model::ROUTE_ADMIN_PATH;
        $this->viewPath = Model::VIEW_BACKEND_PATH;
        // $this->middleware('permission:page inactive', ['only' => ['inactive']]);
        // $this->middleware('permission:page change status', ['only' => ['mark']]);
    }

    public function repository(): BaseRepository
    {
        return $this->pageRepository;
    }
}
