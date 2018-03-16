<?php

namespace App\Http\Controllers\Backend\Core\Page;

use Illuminate\Http\Request;
use HalcyonLaravel\Base\Controllers\Backend\CRUDController as Controller;
use HalcyonLaravel\Base\Repository\BaseRepository as Repository;
use App\Models\Core\Page as Model;

/**
 * Class PagesController.
 */
class PagesController extends Controller
{
    /**
     * PagesController Constructor
     */
    public function __construct(Model $model)
    {
        $this->model = $model;
        $this->repo = new Repository($model);
        parent::__construct();
    }

    /**
     * Specify Model class name.
     *
     * @return mixed
     */
    public function model()
    {
        return Model::class;
    }

    /**
     * @param Request $request
     * @param Model $model | nullable
     *
     * @return array
     */
    public function generateStub(Request $request) : array
    {
        return $request->only(['title']);
    }


    /**
     * Validate input on store
     *
     * @return array
     */
    public function storeRules(Request $request) : array
    {
        return [
            'title' => 'required|unique:pages,id'
        ];
    }
    
    /**
     * Validate input on update
     *
     * @param Model $model | nullable
     *
     * @return array
     */
    public function updateRules(Request $request, $model) : array
    {
        return [
        ];
    }
}
