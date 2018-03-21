<?php

namespace HalcyonLaravel\Base\Repository;

use DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use HalcyonLaravel\Base\Traits\Baseable;

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

use HalcyonLaravel\Base\Exceptions\RepositoryException;

class BaseRepository
{
    /**
     * @param Model $model
     */
    protected $model;

    /**
     * BaseRepository Constructor
     */
    public function __construct(Model $model)
    {
        $this->model =  $model;
    }

    /**
     * @param array $data
     *
     * @return QueryBuilder $query
     */
    public function table(array $request) : Builder
    {
        $fillable = array_merge($this->model->getFillable(), ['updated_at']);
        $query = $this->model->select($fillable);

        if (method_exists(app(get_class($this->model)), 'bootSoftDeletes')) {
            if (isset($request['trashOnly']) && $request['trashOnly']) {
                $query->onlyTrashed();
            }
        }

        return $query;
    }
    /**
     | ------------------------------------------------------------
     |
     |                  CRUD Actions
     |
     | ------------------------------------------------------------
     */

    /**
      * This will handle DB transaction actiosn
      *
      * @param function $closure
      * @return mixed $data
      * @throws Exception $e
      */
    public function action($closure)
    {
        try {
            DB::beginTransaction();
            $data = $closure();
            DB::commit();
            return $data;
        } catch (\Exception $e) {
            DB::rollback();
            $this->_handleErrors($e);
        }
    }

    /**
     * Handle inaccessible methods
     */
    public function __call($name, $args)
    {
        if (in_array($name, [
            'storing', 'stored',
            'updating', 'updated',
            'deleting', 'deleted',
            'restoring', 'restored',
            'purging', 'purged',
        ])) {
            switch ($name) {
                case 'storing':
                    event(new BaseStoringEvent);
                break;
                case 'stored':
                    event(new BaseStoredEvent);
                break;
                case 'updating':
                    event(new BaseUpdatingEvent);
                break;
                case 'updated':
                    event(new BaseUpdatedEvent);
                break;
                case 'deleting':
                    event(new BaseDeletingEvent);
                break;
                case 'deleted':
                    event(new BaseDeletedEvent);
                break;
                case 'restoring':
                    event(new BaseRestoringEvent);
                break;
                case 'restored':
                    event(new BaseRestoredEvent);
                break;
                case 'purging':
                    event(new BasePurgingEvent);
                break;
                case 'purged':
                    event(new BasePurgedEvent);
                break;
            }
            switch ($name) {
                case 'storing':
                case 'purged':
                case 'deleting':
                case 'deleted':
                case 'purging':
                case 'restoring':
                case 'restored':
                    return $args[0];
                break;
                case 'stored':
                case 'updating':
                case 'updated':
                    return $args[1];
                break;
            }
        }

        $this->_handleErrors(trans('base::errors.function_not_found', ['functionName' => $name]));
    }

    /**
     * Handle exception errors
     * @param Exception|String $e
     * @throws Exception $message
     */
    private function _handleErrors($e)
    {
        $message = $e instanceof \Exception ? $e->getMessage() : $e;
        throw new \Exception($message);
    }

    /**
     * @param array|mixed
     * @return mixed
     * @throws \Exception
     */
    public function store($data)
    {
        return $this->action(function () use ($data) {
            $data = $this->storing($data);
            $model = $this->model::create($data);
            return $this->stored($data, $model);
        });
    }

    /**
     * @param array|mixed
     * @return mixed
     * @throws Exception
     */
    public function update($data, $model)
    {
        return $this->action(function () use ($data, $model) {
            $model = $this->updating($data, $model);
            $model->update($data);
            return $this->updated($data, $model);
        });
    }


    /**
     * @param Model $model
     *
     * @throws \Exception
     * @return $model
     */
    public function destroy($model)
    {
        return $this->action(function () use ($model) {
            $model = $this->deleting($model);
            $model->delete();
            return $this->deleted($model);
        });
    }

    /**
     * @param Model $model
     *
     * @throws Exception
     *
     * @return mixed
     */
    public function restore($model)
    {
        if (is_null($model->deleted_at)) {
            throw RepositoryException::notDeleted();
        }

        return $this->action(function () use ($model) {
            $model = $this->restoring($model);
            $model->restore();
            return $this->restored($model);
        });
    }

    /**
     * @param Model $model
     *
     * @throws Exception
     * @return mixed
     */
    public function purge($model)
    {
        if (is_null($model->deleted_at)) {
            throw RepositoryException::notDeleted();
        }

        return $this->action(function () use ($model) {
            $model = $this->purging($model);
            $model->forceDelete();
            return $this->purged($model);
        });
    }
}
