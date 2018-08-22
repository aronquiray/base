<?php

namespace HalcyonLaravel\Base\Repository;

use HalcyonLaravel\Base\Models\Model;

abstract class ObserverContract
{
    abstract public static function storing(array $data) :array;
    abstract public static function stored(Model $model, array $data) :Model;

    abstract public static function updating(Model $model, array $data) :Model;
    abstract public static function updated(Model $model, array $data, array $oldModel) :Model;

    abstract public static function deleting(Model $model) :Model;
    abstract public static function deleted(Model $model) :Model;

    abstract public static function restoring(Model $model) :Model;
    abstract public static function restored(Model $model) :Model;

    abstract public static function purging(Model $model) :Model;
    abstract public static function purged(Model $model) :Model;
}
