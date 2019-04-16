<?php

namespace HalcyonLaravel\Base\Tests\Http\Controllers\Backend\Core\Page;

use HalcyonLaravel\Base\Controllers\Backend\StatusController;
use HalcyonLaravel\Base\Repository\BaseRepositoryInterface;
use HalcyonLaravel\Base\Tests\Models\Content as Model;
use HalcyonLaravel\Base\Tests\Repositories\ContentRepository;

/**
 * Class ContentStatusController
 *
 * @package HalcyonLaravel\Base\Tests\Http\Controllers\Backend\Core\Page
 */
class ContentStatusController extends StatusController
{
    protected $contentRepository;

    public function __construct(ContentRepository $contentRepository)
    {
        $this->contentRepository = $contentRepository;

        $this->routePath = Model::ROUTE_ADMIN_PATH;
        $this->viewPath = Model::VIEW_BACKEND_PATH;
        // $this->middleware('permission:page inactive', ['only' => ['inactive']]);
        // $this->middleware('permission:page change status', ['only' => ['mark']]);
    }

    public function repository(): BaseRepositoryInterface
    {
        return $this->contentRepository;
    }
}
