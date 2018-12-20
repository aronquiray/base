<?php

namespace HalcyonLaravel\Base\Repository;

use HalcyonLaravel\Base\Models\Contracts\BaseModel;

class DefaultObserver extends ObserverContract
{
    /**
     * @param array $data
     *
     * @return array
     */
    public static function storing(array $data): array
    {
        return $data;
    }

    /**
     * @param BaseModel $model
     * @param array     $data
     *
     * @return BaseModel
     */
    public static function stored(BaseModel $model, array $data): BaseModel
    {
        return $model;
    }

    /**
     * @param BaseModel $model
     * @param array     $data
     *
     * @return BaseModel
     */
    public static function updating(BaseModel $model, array $data): BaseModel
    {
        return $model;
    }

    /**
     * @param BaseModel $model
     * @param array     $data
     * @param array     $oldModel
     *
     * @return BaseModel
     */
    public static function updated(BaseModel $model, array $data, array $oldModel): BaseModel
    {
        return $model;
    }

    /**
     * @param BaseModel $model
     *
     * @return BaseModel
     */
    public static function deleting(BaseModel $model): BaseModel
    {
        return $model;
    }

    /**
     * @param BaseModel $model
     *
     * @return BaseModel
     */
    public static function deleted(BaseModel $model): BaseModel
    {
        return $model;
    }

    /**
     * @param BaseModel $model
     *
     * @return BaseModel
     */
    public static function restoring(BaseModel $model): BaseModel
    {
        return $model;
    }

    /**
     * @param BaseModel $model
     *
     * @return BaseModel
     */
    public static function restored(BaseModel $model): BaseModel
    {
        return $model;
    }

    /**
     * @param BaseModel $model
     *
     * @return BaseModel
     */
    public static function purging(BaseModel $model): BaseModel
    {
        return $model;
    }

    /**
     * @param BaseModel $model
     *
     * @return BaseModel
     */
    public static function purged(BaseModel $model): BaseModel
    {
        return $model;
    }
}
