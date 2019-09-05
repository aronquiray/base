<?php

namespace HalcyonLaravel\Base\Repository;

use Closure;
use HalcyonLaravel\Base\Criterion\Eloquent\OnlyTrashedCriteria;
use HalcyonLaravel\Base\Criterion\Eloquent\ThisEqualThatCriteria;
use HalcyonLaravel\Base\Models\Contracts\ModelStatusContract;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use InvalidArgumentException;
use Prettus\Repository\Contracts\CacheableInterface;
use Prettus\Repository\Eloquent\BaseRepository as PrettusBaseRepository;
use Prettus\Repository\Events\RepositoryEntityUpdated;
use Prettus\Repository\Traits\CacheableRepository;

/**
 * Class BaseRepository
 *
 * @package HalcyonLaravel\Base\Repository
 */
abstract class BaseRepository extends PrettusBaseRepository implements CacheableInterface, BaseRepositoryInterface
{
    use CacheableRepository;

    /**
     * @param  array|null  $request
     * @param  array  $fields
     * @param  bool  $isAllFillable
     *
     * @return mixed
     * @throws \Prettus\Repository\Exceptions\RepositoryException
     */
    public function table(
        array $request = null,
        array $fields = [],
        bool $isAllFillable = true
    ) {
        $isHasSoftDelete = is_class_uses_deep($this->model, SoftDeletes::class);

        $tableName = $this->model->getTable();

        foreach (['id', 'created_at', 'updated_at'] as $column) {
            if (Schema::hasColumn($tableName, $column)) {
                $fields[] = $column;
            }
        }

        if ($isHasSoftDelete) {
            $fields[] = 'deleted_at';
        }

        $fields = empty($fields) ? ['*'] : $fields;
        $columns = null;
        if ($isAllFillable) {
            $columns = array_merge($this->model->getFillable(), $fields);
        } else {
            $columns = $fields;
        }

        if ($this->model instanceof ModelStatusContract) {
            $statusKey = $this->model->statusKeyName();
            if (array_key_exists($statusKey, $request)) {
                $this->pushCriteria(new ThisEqualThatCriteria($statusKey, $request[$statusKey]));
            }
        }
        if ($isHasSoftDelete) {
            if (isset($request['trashOnly']) && $request['trashOnly']) {
                $this->pushCriteria(new OnlyTrashedCriteria);
            }
        }

        $this->applyCriteria();
        $this->applyScope();

        $builder = $this->model->select($columns);

        $this->resetModel();
        $this->resetScope();

        return $builder;
    }

    /**
     * @param  array  $attributes
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
            $data = $this->observer()::storing($attributes);
            $model = parent::create($data);
            return $this->observer()::stored($model, $attributes);
        });
    }

    /**
     * @return bool
     * @throws \Throwable
     */
    private function hasObserver(): bool
    {
        $has = !is_null($this->observer());

        if ($has) {
            // check instance
            if (!(app($this->observer()) instanceof ObserverContract)) {
                throw new InvalidArgumentException($this->observer().' must instance of '.ObserverContract::class.'.');
            }
        }

        return $has;
    }

    /**
     * @return string|null
     */
    protected function observer()
    {
        return null;
    }

    /**
     * @param  \Closure  $closure
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
     * @param  array  $attributes
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
            $model = $this->observer()::updating($model, $attributes);
            $model = parent::update($attributes, $model->id);
            return $this->observer()::updated($model, $attributes, $oldModel);
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
            $model = $this->observer()::deleting($model);
            parent::delete($model->id);

            return $this->observer()::deleted($model);
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
        $this->pushCriteria(new OnlyTrashedCriteria);
        $model = $this->find($id);

        if (!$this->hasObserver()) {
            $model->restore();
            event(new RepositoryEntityUpdated($this, $model));
            return $model;
        }

        return $this->action(function () use ($model) {
            $model = $this->observer()::restoring($model);
            $model->restore();

            event(new RepositoryEntityUpdated($this, $model));

            return $this->observer()::restored($model);
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
        $this->pushCriteria(new OnlyTrashedCriteria);
        $model = $this->find($id);

        if (!$this->hasObserver()) {
            $model->forceDelete();
            event(new RepositoryEntityUpdated($this, $model));
            return $model;
        }

        return $this->action(function () use ($model) {
            $model = $this->observer()::purging($model);
            $model->forceDelete();

            event(new RepositoryEntityUpdated($this, $model));
            return $this->observer()::purged($model);
        });
    }
}
