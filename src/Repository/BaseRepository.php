<?php

namespace HalcyonLaravel\Base\Repository;

use DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use HalcyonLaravel\Base\Traits\Baseable;

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
    public function table(array $request = null) : Builder
    {
        $isHasSoftDelete = method_exists($this->model, 'bootSoftDeletes');

        $otherFields = ['updated_at'];
        if ($isHasSoftDelete) {
            $otherFields[] = 'deleted_at';
        }

        $fillable = array_merge($this->model->getFillable(), $otherFields);
        $query = $this->model->select($fillable);

        if ($isHasSoftDelete) {
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
            return $this->model::create($data);
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
            $model->update($data);
            return $model;
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
            $model->delete();
            return $model;
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
            $model->restore();
            return $model;
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
            $model->forceDelete();
            return $model;
        });
    }
}
