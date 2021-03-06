<?php

namespace HalcyonLaravel\Base\Repository;

use Closure;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use InvalidArgumentException;

/**
 * Class ObserverContract
 *
 * @package HalcyonLaravel\Base\Repository
 */
abstract class ObserverContract
{
    /**
     * @param  array  $data
     *
     * @return array
     */
    abstract public static function storing(array $data): array;

    /**
     * @param       $model
     * @param  array  $data
     *
     * @return mixed
     */
    abstract public static function stored($model, array $data);

    /**
     * @param       $model
     * @param  array  $data
     *
     * @return mixed
     */
    abstract public static function updating($model, array $data);

    /**
     * @param       $model
     * @param  array  $data
     * @param  array  $oldModel
     *
     * @return mixed
     */
    abstract public static function updated($model, array $data, array $oldModel);

    /**
     * @param $model
     *
     * @return mixed
     */
    abstract public static function deleting($model);

    /**
     * @param $model
     *
     * @return mixed
     */
    abstract public static function deleted($model);

    /**
     * @param $model
     *
     * @return mixed
     */
    abstract public static function restoring($model);

    /**
     * @param $model
     *
     * @return mixed
     */
    abstract public static function restored($model);

    /**
     * @param $model
     *
     * @return mixed
     */
    abstract public static function purging($model);

    /**
     * @param $model
     *
     * @return mixed
     */
    abstract public static function purged($model);

    /**
     * @param  string  $type
     * @param  \Illuminate\Database\Eloquent\Model  $model
     * @param  \Closure  $closure
     */
    protected static function checkBeforeDelete(string $type, Model $model, Closure $closure)
    {
        if (!in_array($type, ['purge', 'destroy'])) {
            throw new InvalidArgumentException('Invalid parameter in '.__METHOD__);
        }

        $isHasSoftDelete = is_class_uses_deep($model, SoftDeletes::class);

        if ($isHasSoftDelete && $type == 'purge' OR
            !$isHasSoftDelete && $type == 'destroy') {
            $closure();
        }
    }
}
