<?php

namespace HalcyonLaravel\Base\Repository;

use HalcyonLaravel\Base\Models\Model;

class DefaultObserver extends ObserverContract
{
    public static function storing(array $data) :array
    {
        return $data;
    }

    public static function stored(Model $model, array $data) :Model
    {
        return $model;
    }

    public static function updating(Model $model, array $data) :Model
    {
        return $model;
    }

    public static function updated(Model $model, array $data, array $oldModel) :Model
    {
        return $model;
    }

    public static function deleting(Model $model) :Model
    {
        return $model;
    }

    public static function deleted(Model $model) :Model
    {
        return $model;
    }

    public static function restoring(Model $model) :Model
    {
        return $model;
    }

    public static function restored(Model $model) :Model
    {
        return $model;
    }

    public static function purging(Model $model) :Model
    {
        return $model;
    }

    public static function purged(Model $model) :Model
    {
        return $model;
    }
}