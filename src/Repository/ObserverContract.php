<?php

namespace HalcyonLaravel\Base\Repository;

use HalcyonLaravel\Base\Models\Model;

abstract class ObserverContract
{
    /**
     * @param array $data
     * @return array
     */
    abstract public static function storing(array $data): array;

    /**
     * @param \HalcyonLaravel\Base\Models\Model $model
     * @param array $data
     * @return \HalcyonLaravel\Base\Models\Model
     */
    abstract public static function stored(Model $model, array $data): Model;

    /**
     * @param \HalcyonLaravel\Base\Models\Model $model
     * @param array $data
     * @return \HalcyonLaravel\Base\Models\Model
     */
    abstract public static function updating(Model $model, array $data): Model;

    /**
     * @param \HalcyonLaravel\Base\Models\Model $model
     * @param array $data
     * @param array $oldModel
     * @return \HalcyonLaravel\Base\Models\Model
     */
    abstract public static function updated(Model $model, array $data, array $oldModel): Model;

    /**
     * @param \HalcyonLaravel\Base\Models\Model $model
     * @return \HalcyonLaravel\Base\Models\Model
     */
    abstract public static function deleting(Model $model): Model;

    /**
     * @param \HalcyonLaravel\Base\Models\Model $model
     * @return \HalcyonLaravel\Base\Models\Model
     */
    abstract public static function deleted(Model $model): Model;

    /**
     * @param \HalcyonLaravel\Base\Models\Model $model
     * @return \HalcyonLaravel\Base\Models\Model
     */
    abstract public static function restoring(Model $model): Model;

    /**
     * @param \HalcyonLaravel\Base\Models\Model $model
     * @return \HalcyonLaravel\Base\Models\Model
     */
    abstract public static function restored(Model $model): Model;

    /**
     * @param \HalcyonLaravel\Base\Models\Model $model
     * @return \HalcyonLaravel\Base\Models\Model
     */
    abstract public static function purging(Model $model): Model;

    /**
     * @param \HalcyonLaravel\Base\Models\Model $model
     * @return \HalcyonLaravel\Base\Models\Model
     */
    abstract public static function purged(Model $model): Model;
}
