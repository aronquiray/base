<?php

namespace HalcyonLaravel\Base\Tests\Http\Controllers\Backend\Core\Page;

use HalcyonLaravel\Base\BaseableOptions;
use HalcyonLaravel\Base\Controllers\Backend\CRUDController as Controller;
use HalcyonLaravel\Base\Repository\BaseRepositoryInterface;
use HalcyonLaravel\Base\Tests\Repositories\PageRepository;
use Illuminate\Database\Eloquent\Model as IlluminateModel;
use Illuminate\Http\Request;

/**
 * Class PagesController.
 */
class PagesController extends Controller
{
    protected $pageRepository;

    /**
     * PagesController constructor.
     *
     * @param \HalcyonLaravel\Base\Tests\Repositories\PageRepository $pageRepository
     *
     * @throws \Prettus\Repository\Exceptions\RepositoryException
     */
    public function __construct(PageRepository $pageRepository)
    {
        $this->pageRepository = $pageRepository;
        parent::__construct();
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @param \Illuminate\Database\Eloquent\Model|null $model
     * @return \HalcyonLaravel\Base\BaseableOptions
     */
    public function crudRules(Request $request, IlluminateModel $model = null): BaseableOptions
    {
        return BaseableOptions::create()->storeRules([
                'title' => 'required|unique:pages,id',
            ])->storeRuleMessages([
                'title.required' => 'The title field is required.',
            ])->updateRules([
                'title' => 'required|unique:pages,id',
            ])->updateRuleMessages([
                'title.required' => 'The title field is required.',
            ]);
    }

    public function testForMethodNotFound()
    {
        $this->pageRepository->imNotExist();
    }

    // public function testGetModelWithFields()
    // {
    //     $this->getModel($routeKeyName);
    // }
    public function repository(): BaseRepositoryInterface
    {
        return $this->pageRepository;
    }

    /**
     * @param \Illuminate\Http\Request            $request
     * @param \Illuminate\Database\Eloquent\Model $model
     *
     * @return array
     */
    public function generateStub(Request $request, IlluminateModel $model = null): array
    {
        return $request->only(['title', 'description', 'status']);
    }
}
