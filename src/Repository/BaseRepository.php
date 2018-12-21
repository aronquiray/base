<?php

namespace HalcyonLaravel\Base\Repository;

use Closure;
use DB;
use Exception;
use HalcyonLaravel\Base\Criterion\Eloquent\LatestCriteria;
use HalcyonLaravel\Base\Criterion\Eloquent\OnlyTrashCriteria;
use Illuminate\Container\Container;
use Prettus\Repository\Contracts\CacheableInterface;
use Prettus\Repository\Eloquent\BaseRepository as PrettusBaseRepository;
use Prettus\Repository\Events\RepositoryEntityUpdated;
use Prettus\Repository\Traits\CacheableRepository;
use Schema;

//use Prettus\Repository\Events\RepositoryEntityUpdated;

abstract class BaseRepository extends PrettusBaseRepository implements CacheableInterface
{
    use CacheableRepository;

    protected $observer = null;

    public function __construct()
    {
        parent::__construct(app(Container::class));
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
     * @param array $attributes
     *
     * @return mixed
     * @throws \Prettus\Validator\Exceptions\ValidatorException
     * @throws \Throwable
     */
    public function create(array $attributes)
    {
        if (!$this->hasObserver()) {
            return parent::create($attributes);
        }

        return $this->action(function () use ($attributes) {
            $data = $this->observer::creating($attributes);
            $model = parent::create($data);
            return $this->observer::created($model, $attributes);
        });
    }

    /**
     * @return bool
     * @throws \Throwable
     */
    private function hasObserver(): bool
    {
        $has = !is_null($this->observer);

        if ($has) {
            // check instance
            throw_if(!($this->observer instanceof ObserverContract), Exception ::class,
                "{$this->observer} muss instance of " . ObserverContract::class . '.');
        }

        return $has;
    }

    /**
     * This will handle DB transaction action
     *
     * @param \Closure $closure
     *
     * @return mixed
     */
    private function action(Closure $closure)
    {
        return DB::transaction(function () use ($closure) {
            return $closure();
        });
    }

    /**
     * @param array $attributes
     * @param       $id
     *
     * @return mixed
     * @throws \Prettus\Validator\Exceptions\ValidatorException
     * @throws \Throwable
     */
    public function update(array $attributes, $id)
    {
        if (!$this->hasObserver()) {
            return parent::update($attributes, $id);
        }

        $model = $this->find($id);

        return $this->action(function () use ($attributes, $model) {
            $oldModel = $model->getOriginal();
            $model = $this->observer::updating($model, $attributes);
            $model = parent::update($attributes, $model->id);
            return $this->observer::updated($model, $attributes, $oldModel);
        });
    }

    /**
     * @param $id
     *
     * @return int|mixed
     * @throws \Throwable
     */
    public function delete($id)
    {
        if (!$this->hasObserver()) {
            return parent::delete($id);
        }

        $model = $this->find($id);

        return $this->action(function () use ($model) {
            $model = $this->observer::deleting($model);
            parent::delete($model->id);

            return $this->observer::deleted($model);
        });
    }

    /**
     * @param $id
     *
     * @return mixed
     * @throws \Prettus\Repository\Exceptions\RepositoryException
     * @throws \Throwable
     */
    public function restore($id)
    {
        $this->pushCriteria(new OnlyTrashCriteria);
        $model = $this->find($id);

        if (!$this->hasObserver()) {
            $model->restore();
            event(new RepositoryEntityUpdated($this, $model));
            return $model;
        }

        return $this->action(function () use ($model) {
            $model = $this->observer::restoring($model);
            $model->restore();

            event(new RepositoryEntityUpdated($this, $model));

            return $this->observer::restored($model);
        });
    }

    /**
     * @param $id
     *
     * @return mixed
     * @throws \Prettus\Repository\Exceptions\RepositoryException
     * @throws \Throwable
     */
    public function purge($id)
    {
        $this->pushCriteria(new OnlyTrashCriteria);
        $model = $this->find($id);

        if (!$this->hasObserver()) {
            $model->forceDelete();
            event(new RepositoryEntityUpdated($this, $model));
            return $model;
        }

        return $this->action(function () use ($model) {
            $model = $this->observer::purging($model);
            $model->forceDelete();

            event(new RepositoryEntityUpdated($this, $model));
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
