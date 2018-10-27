<?php

namespace HalcyonLaravel\Base\Repository;

use HalcyonLaravel\Base\Models\Model;

class DefaultObserver extends ObserverContract
{
    /**
     * @param array $data
     * @return array
     */
    public static function storing(array $data): array
    {
        return $data;
    }

    /**
     * @param \HalcyonLaravel\Base\Models\Model $model
     * @param array $data
     * @return \HalcyonLaravel\Base\Models\Model
     */
    public static function stored(Model $model, array $data): Model
    {
        return $model;
    }

    /**
     * @param \HalcyonLaravel\Base\Models\Model $model
     * @param array $data
     * @return \HalcyonLaravel\Base\Models\Model
     */
    public static function updating(Model $model, array $data): Model
    {
        return $model;
    }

    /**
     * @param \HalcyonLaravel\Base\Models\Model $model
     * @param array $data
     * @param array $oldModel
     * @return \HalcyonLaravel\Base\Models\Model
     */
    public static function updated(Model $model, array $data, array $oldModel): Model
    {
        return $model;
    }

    /**
     * @param \HalcyonLaravel\Base\Models\Model $model
     * @return \HalcyonLaravel\Base\Models\Model
     */
    public static function deleting(Model $model): Model
    {
        return $model;
    }

    /**
     * @param \HalcyonLaravel\Base\Models\Model $model
     * @return \HalcyonLaravel\Base\Models\Model
     */
    public static function deleted(Model $model): Model
    {
        return $model;
    }

    /**
     * @param \HalcyonLaravel\Base\Models\Model $model
     * @return \HalcyonLaravel\Base\Models\Model
     */
    public static function restoring(Model $model): Model
    {
        return $model;
    }

    /**
     * @param \HalcyonLaravel\Base\Models\Model $model
     * @return \HalcyonLaravel\Base\Models\Model
     */
    public static function restored(Model $model): Model
    {
        return $model;
    }

    /**
     * @param \HalcyonLaravel\Base\Models\Model $model
     * @return \HalcyonLaravel\Base\Models\Model
     */
    public static function purging(Model $model): Model
    {
        return $model;
    }

    /**
     * @param \HalcyonLaravel\Base\Models\Model $model
     * @return \HalcyonLaravel\Base\Models\Model
     */
    public static function purged(Model $model): Model
    {
        return $model;
    }
}
