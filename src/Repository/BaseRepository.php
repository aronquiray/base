<?php

namespace HalcyonLaravel\Base\Repository;

use Closure;
use DB;
use HalcyonLaravel\Base\Criterion\Eloquent\LatestCriteria;
use HalcyonLaravel\Base\Criterion\Eloquent\OnlyTrashCriteria;
use HalcyonLaravel\Base\Exceptions\RepositoryException;
use HalcyonLaravel\Base\Models\Contracts\BaseModel;
use Illuminate\Container\Container as Application;
use Prettus\Repository\Contracts\CacheableInterface;
use Prettus\Repository\Eloquent\BaseRepository as PrettusBaseRepository;
use Prettus\Repository\Traits\CacheableRepository;
use Schema;

//use Prettus\Repository\Events\RepositoryEntityUpdated;

abstract class BaseRepository extends PrettusBaseRepository implements CacheableInterface
{
    use CacheableRepository;

    /**
     * @var \HalcyonLaravel\Base\Repository\DefaultObserver
     */
    protected $observer;

    /**
     * BaseRepository constructor.
     */
    public function __construct()
    {
        $this->observer = new DefaultObserver;
        parent::__construct(new Application);
    }

    /**
     * @param array|null $request
     * @param array      $fields
     * @param bool       $isAllFillable
     *
     * @return mixed
     * @throws \Prettus\Repository\Exceptions\RepositoryException
     */
    public function table(array $request = null, array $fields = [], bool $isAllFillable = true)
    {
        $isHasSoftDelete = method_exists($this->model, 'bootSoftDeletes');

        if (Schema::hasColumn($this->model->getTable(), 'updated_at')) {
            $fields[] = 'updated_at';
        }
        if ($isHasSoftDelete) {
            $fields[] = 'deleted_at';
        }

        $columns = null;
        if ($isAllFillable) {
            $columns = array_merge($this->model->getFillable(), $fields);
        } else {
            $columns = $fields;
        }

        if ($isHasSoftDelete) {
            if (isset($request['trashOnly']) && $request['trashOnly']) {
                $this->pushCriteria(new OnlyTrashCriteria);
            }
        }

        $this->pushCriteria(new LatestCriteria);

        return $this->all($columns);
    }

    /**
     * @param array $data
     *
     * @return BaseModel
     */
    public function store(array $data): BaseModel
    {
        return $this->action(function () use ($data) {
            $data = $this->observer::storing($data);
            $model = $this->create($data);

            return $this->observer::stored($model, $data);
        });
    }

    /**
     * This will handle DB transaction action
     *
     * Closure $closure
     *
     * @return mixed
     */
    public function action(Closure $closure)
    {
        return DB::transaction(function () use ($closure) {
            return $closure();
        });
    }

    /**
     * @param array $data
     * @param       $modelId
     *
     * @return BaseModel
     */
    public function update(array $data, $modelId): BaseModel
    {
        $model = $this->find($modelId);

        return $this->action(function () use ($data, $model) {
            $oldModel = $model->getOriginal();
            $model = $this->observer::updating($model, $data);
            $model = parent::update($data, $model->id);

            return $this->observer::updated($model, $data, $oldModel);
        });
    }

    /**
     * @param BaseModel $model
     *
     * @return BaseModel
     */
    public function destroy(BaseModel $model): BaseModel
    {
        return $this->action(function () use ($model) {
            $model = $this->observer::deleting($model);
            $this->delete($model->id);

            return $this->observer::deleted($model);
        });
    }

    /**
     * @param BaseModel $model
     *
     * @return BaseModel
     */
    public function restore(BaseModel $model): BaseModel
    {
        if (is_null($model->deleted_at)) {
            throw new RepositoryException(403, trans('base::exceptions.not_deleted'));
        }

        return $this->action(function () use ($model) {
            $model = $this->observer::restoring($model);
            $model->restore();

            //event(new RepositoryEntityUpdated(new static, $model));

            return $this->observer::restored($model);
        });
    }

    /**
     * @param BaseModel $model
     *
     * @return BaseModel
     */
    public function purge(BaseModel $model): BaseModel
    {
        if (is_null($model->deleted_at)) {
            throw new RepositoryException(403, trans('base::exceptions.not_deleted'));
        }

        return $this->action(function () use ($model) {
            $model = $this->observer::purging($model);
            $model->forceDelete();

            return $this->observer::purged($model);
        });
    }

    /**
     * @param ObserverContract $observer
     */
    protected function setObserver(ObserverContract $observer)
    {
        $this->observer = $observer;
    }
}
