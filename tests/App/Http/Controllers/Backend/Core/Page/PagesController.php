<?php

namespace HalcyonLaravel\Base\Tests\Http\Controllers\Backend\Core\Page;

use HalcyonLaravel\Base\BasableOptions;
use HalcyonLaravel\Base\Controllers\Backend\CRUDController as Controller;
use HalcyonLaravel\Base\Repository\BaseRepository as Repository;
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
     * @param PageRepository $pageRepository
     */
    public function __construct(PageRepository $pageRepository)
    {
        $this->pageRepository = $pageRepository;
        parent::__construct();
    }


    /**
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function generateStub(Request $request): array
    {
        return $request->only(['title', 'description', 'status']);
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @param \Illuminate\Database\Eloquent\Model|null $model
     * @return \HalcyonLaravel\Base\BasableOptions
     */
    public function crudRules(Request $request, IlluminateModel $model = null): BasableOptions
    {
        return BasableOptions::create()->storeRules([
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
    public function repository(): Repository
    {
        return $this->pageRepository;
    }
}
