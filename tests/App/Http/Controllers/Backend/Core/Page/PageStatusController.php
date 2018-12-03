<?php

namespace App\Http\Controllers\Backend\Core\Page;

use App\Models\Core\Page as Model;
use App\Repositories\PageRepository;
use HalcyonLaravel\Base\Controllers\Backend\StatusController as Controller;
use HalcyonLaravel\Base\Repository\BaseRepository;

/**
 * Class ContentStatusController.
 */
class PageStatusController extends Controller
{
    protected $pageRepository;

    public function __construct(PageRepository $pageRepository)
    {
        $this->pageRepository = $pageRepository;

        $this->route_path = Model::ROUTE_ADMIN_PATH;
        $this->view_path = Model::VIEW_BACKEND_PATH;
        parent::__construct();
        // $this->middleware('permission:page inactive', ['only' => ['inactive']]);
        // $this->middleware('permission:page change status', ['only' => ['mark']]);
    }

    public function repository(): BaseRepository
    {
        return $this->pageRepository;
    }
}
