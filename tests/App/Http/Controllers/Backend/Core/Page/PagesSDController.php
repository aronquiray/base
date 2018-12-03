<?php

namespace App\Http\Controllers\Backend\Core\Page;

use App\Repositories\PageDeleteRepository;
use HalcyonLaravel\Base\BasableOptions;
use HalcyonLaravel\Base\Controllers\Backend\CRUDController as Controller;
use HalcyonLaravel\Base\Repository\BaseRepository;
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
     * @param \App\Repositories\PageDeleteRepository $pageDeleteRepository
     */
    public function __construct(PageDeleteRepository $pageDeleteRepository)
    {
        $this->pageDeleteRepository = $pageDeleteRepository;
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
     * Validate input on store/update
     *
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

    public function repository(): BaseRepository
    {
        return $this->pageDeleteRepository;
    }
}
