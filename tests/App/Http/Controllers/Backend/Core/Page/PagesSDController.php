<?php

namespace HalcyonLaravel\Base\Tests\Http\Controllers\Backend\Core\Page;

use HalcyonLaravel\Base\BaseableOptions;
use HalcyonLaravel\Base\Controllers\Backend\CRUDController as Controller;
use HalcyonLaravel\Base\Repository\BaseRepository;
use HalcyonLaravel\Base\Tests\Repositories\PageDeleteRepository;
use Illuminate\Database\Eloquent\Model as IlluminateModel;
use Illuminate\Http\Request;

/**
 * Class PagesController.
 */
class PagesSDController extends Controller
{
    protected $pageDeleteRepository;

    /**
     * PagesSDController constructor.
     *
     * @param \HalcyonLaravel\Base\Tests\Repositories\PageDeleteRepository $pageDeleteRepository
     *
     * @throws \Prettus\Repository\Exceptions\RepositoryException
     */
    public function __construct(PageDeleteRepository $pageDeleteRepository)
    {
        $this->pageDeleteRepository = $pageDeleteRepository;
        parent::__construct();
    }

    /**
     * Validate input on store/update
     *
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

    public function repository(): BaseRepository
    {
        return $this->pageDeleteRepository;
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
