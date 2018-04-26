<?php

namespace App\Http\Controllers\Backend\Core\Page;

use Illuminate\Http\Request;
use HalcyonLaravel\Base\Controllers\Backend\CRUDController as Controller;
use HalcyonLaravel\Base\Repository\BaseRepository as Repository;
use App\Models\Core\PageSoftDelete as Model;


use HalcyonLaravel\Base\Events\BaseStoringEvent;
use HalcyonLaravel\Base\Events\BaseStoredEvent;
use HalcyonLaravel\Base\Events\BaseUpdatingEvent;
use HalcyonLaravel\Base\Events\BaseUpdatedEvent;
use HalcyonLaravel\Base\Events\BaseDeletingEvent;
use HalcyonLaravel\Base\Events\BaseDeletedEvent;
use HalcyonLaravel\Base\Events\BaseRestoringEvent;
use HalcyonLaravel\Base\Events\BaseRestoredEvent;
use HalcyonLaravel\Base\Events\BasePurgingEvent;
use HalcyonLaravel\Base\Events\BasePurgedEvent;

/**
 * Class PagesController.
 */
class PagesSDController extends Controller
{
    /**
     * PagesController Constructor
     */
    public function __construct(Model $model)
    {
        $this->model = $model;
        $this->repo = new Repository($model);
        $this->_initRepositoryObservers();
        parent::__construct();
    }


    /**
     *  Available observers:
        'storing', 'stored',
        'updating', 'updated',
        'deleting', 'deleted',
        'restoring', 'restored',
        'purging', 'purged',
     *
     * You can remove one of them, it will run default bahavior on base class.
     * @author lloricode@gmail.com
     */
    private function _initRepositoryObservers()
    {
        $this->repo->storing = function ($data) {
            event(new BaseStoringEvent);
            // dd($data);
            return $data;
        };
        $this->repo->stored = function ($data, $model) {
            event(new BaseStoredEvent);
            // dd($data, $model);
            return $model;
        };
        $this->repo->updating = function ($data, $model) {
            event(new BaseUpdatingEvent);
            // dd($data, $model);
            return $model;
        };
        $this->repo->updated = function ($data, $model) {
            event(new BaseUpdatedEvent);
            // dd($data, $model);
            return $model;
        };
        $this->repo->deleting = function ($model) {
            event(new BaseDeletingEvent);
            // dd($model);
            return $model;
        };
        $this->repo->deleted = function ($model) {
            event(new BaseDeletedEvent);
            // dd($model);
            return $model;
        };
        $this->repo->restoring = function ($model) {
            event(new BaseRestoringEvent);
            // dd($model);
            return $model;
        };
        $this->repo->restored = function ($model) {
            event(new BaseRestoredEvent);
            // dd($model);
            return $model;
        };
        $this->repo->purging = function ($model) {
            event(new BasePurgingEvent);
            // dd($model);
            return $model;
        };
        $this->repo->purged = function ($model) {
            event(new BasePurgedEvent);
            // dd($model);
            return $model;
        };
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
