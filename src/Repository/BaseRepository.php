<?php

namespace HalcyonLaravel\Base\Repository;

use HalcyonLaravel\Base\Exceptions\RepositoryException;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class BaseRepository
{
    /**
     * @var \Illuminate\Database\Eloquent\Model
     */
    protected $model;

    /**
     * @var \HalcyonLaravel\Base\Repository\DefaultObserver
     */
    protected $observer;

    /**
     * BaseRepository constructor.
     *
     * @param \Illuminate\Database\Eloquent\Model $model
     */
    public function __construct(Model $model)
    {
        $this->model = $model;
        $this->observer = new DefaultObserver;
    }

    /**
     * @param array|null $request
     * @param array $fields
     * @param bool $isAllFillable
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function table(array $request = null, array $fields = [], bool $isAllFillable = true): Builder
    {
        $isHasSoftDelete = method_exists($this->model, 'bootSoftDeletes');

        if (Schema::hasColumn($this->model->getTable(), 'updated_at')) {
            $fields[] = 'updated_at';
        }
        if ($isHasSoftDelete) {
            $fields[] = 'deleted_at';
        }

        if ($isAllFillable) {
            $fillable = array_merge($this->model->getFillable(), $fields);
            $query = $this->model->select($fillable);
        } else {
            $query = $this->model->select($fields);
        }

        if ($isHasSoftDelete) {
            if (isset($request['trashOnly']) && $request['trashOnly']) {
                $query->onlyTrashed();
            }
        }

        return $query;
    }

    /**
     * @param array|mixed
     * @return mixed
     * @throws \Exception
     */
    public function store($data)
    {
        return $this->action(function () use ($data) {
            $data = $this->observer::storing($data);
            $model = $this->model::create($data);

            return $this->observer::stored($model, $data);
        });
    }
    /**
     * | ------------------------------------------------------------
     * |
     * |                  CRUD Actions
     * |
     * | ------------------------------------------------------------
     */

    /**
     * This will handle DB transaction action
     *
     * @param $closure
     * @return mixed
     */
    public function action($closure)
    {
        return DB::transaction(function () use ($closure) {
            return $closure();
        });
    }

    /**
     * @param $data
     * @param $model
     * @return mixed
     */
    public function update($data, $model)
    {
        return $this->action(function () use ($data, $model) {
            $oldModel = $model->getOriginal();
            $model = $this->observer::updating($model, $data);
            $model->update($data);

            return $this->observer::updated($model, $data, $oldModel);
        });
    }

    /**
     * @param $model
     * @return mixed
     */
    public function destroy($model)
    {
        return $this->action(function () use ($model) {
            $model = $this->observer::deleting($model);
            $model->delete();

            return $this->observer::deleted($model);
        });
    }

    /**
     * @param $model
     * @return mixed
     */
    public function restore($model)
    {
        if (is_null($model->deleted_at)) {
            throw RepositoryException::notDeleted();
        }

        return $this->action(function () use ($model) {
            $model = $this->observer::restoring($model);
            $model->restore();

            return $this->observer::restored($model);
        });
    }

    /**
     * @param $model
     * @return mixed
     */
    public function purge($model)
    {
        if (is_null($model->deleted_at)) {
            throw RepositoryException::notDeleted();
        }

        return $this->action(function () use ($model) {
            $model = $this->observer::purging($model);
            $model->forceDelete();

            return $this->observer::purged($model);
        });
    }

    /**
     * @param \HalcyonLaravel\Base\Repository\ObserverContract $observer
     */
    protected function setObserver(ObserverContract $observer)
    {
        $this->observer = $observer;
    }
}
