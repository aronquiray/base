<?php

namespace App\Http\Controllers\Backend\Core\Page;

use Illuminate\Http\Request;
use HalcyonLaravel\Base\Controllers\Backend\CRUDController as Controller;
use HalcyonLaravel\Base\Repository\BaseRepository as Repository;
use App\Models\Core\Page as Model;
use HalcyonLaravel\Base\BasableOptions;
use Illuminate\Database\Eloquent\Model as IlluminateModel;

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
        return $request->only(['title', 'description', 'status']);
    }


    /**
     * Validate input on store/update
     *
     * @return array
     */
    public function crudRules(Request $request, IlluminateModel $model = null) : BasableOptions
    {
        return BasableOptions::create()
            ->storeRules([
                'title' => 'required|unique:pages,id'
            ])
            ->storeRuleMessages([
                'title.required' => 'The title field is required.',
            ])
            ->updateRules([
                'title' => 'required|unique:pages,id'
            ])
            ->updateRuleMessages([
                'title.required' => 'The title field is required.',
            ]);
    }
    
    public function testForMethodNotFound()
    {
        $this->repo->imNotExist();
    }

    // public function testGetModelWithFields()
    // {
    //     $this->getModel($routeKeyName);
    // }
}
