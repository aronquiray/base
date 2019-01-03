<?php

namespace HalcyonLaravel\Base\Tests\Http\Controllers\Backend\Core\Page;

use HalcyonLaravel\Base\BaseableOptions;
use HalcyonLaravel\Base\Controllers\Backend\CRUDController as Controller;
use HalcyonLaravel\Base\Repository\BaseRepository as Repository;
use HalcyonLaravel\Base\Tests\Repositories\PageObserverRepository;
use Illuminate\Database\Eloquent\Model as IlluminateModel;
use Illuminate\Http\Request;

/**
 * Class PagesObserverController.
 */
class PagesObserverController extends Controller
{
    protected $pageObserverRepository;

    /**
     * PagesObserverController constructor.
     *
     * @param \HalcyonLaravel\Base\Tests\Repositories\PageObserverRepository $pageRepository
     */
    public function __construct(PageObserverRepository $pageRepository)
    {
        $this->pageObserverRepository = $pageRepository;
        parent::__construct();
    }


    /**
     * @param \Illuminate\Http\Request $request
     *
     * @return array
     */
    public function generateStub(Request $request): array
    {
        return $request->only(['title', 'description', 'status']);
    }

    /**
     * @param \Illuminate\Http\Request                 $request
     * @param \Illuminate\Database\Eloquent\Model|null $model
     *
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
        $this->pageObserverRepository->imNotExist();
    }

    // public function testGetModelWithFields()
    // {
    //     $this->getModel($routeKeyName);
    // }
    public function repository(): Repository
    {
        return $this->pageObserverRepository;
    }
}
