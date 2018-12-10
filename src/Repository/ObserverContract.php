<?php

namespace HalcyonLaravel\Base\Repository;

use HalcyonLaravel\Base\Models\Contracts\BaseModel;

abstract class ObserverContract
{
    /**
     * @param array $data
     * @return array
     */
    abstract public static function storing(array $data): array;

    /**
     * @param BaseModel $model
     * @param array $data
     * @return BaseModel
     */
    abstract public static function stored(BaseModel $model, array $data): BaseModel;

    /**
     * @param BaseModel $model
     * @param array $data
     * @return BaseModel
     */
    abstract public static function updating(BaseModel $model, array $data): BaseModel;

    /**
     * @param BaseModel $model
     * @param array $data
     * @param array $oldModel
     * @return BaseModel
     */
    abstract public static function updated(BaseModel $model, array $data, array $oldModel): BaseModel;

    /**
     * @param BaseModel $model
     * @return BaseModel
     */
    abstract public static function deleting(BaseModel $model): BaseModel;

    /**
     * @param BaseModel $model
     * @return BaseModel
     */
    abstract public static function deleted(BaseModel $model): BaseModel;

    /**
     * @param BaseModel $model
     * @return BaseModel
     */
    abstract public static function restoring(BaseModel $model): BaseModel;

    /**
     * @param BaseModel $model
     * @return BaseModel
     */
    abstract public static function restored(BaseModel $model): BaseModel;

    /**
     * @param BaseModel $model
     * @return BaseModel
     */
    abstract public static function purging(BaseModel $model): BaseModel;

    /**
     * @param BaseModel $model
     * @return BaseModel
     */
    abstract public static function purged(BaseModel $model): BaseModel;
}
