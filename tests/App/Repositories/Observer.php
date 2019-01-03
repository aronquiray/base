<?php
/**
 * Created by PhpStorm.
 * User: Lloric Mayuga Garcia <lloricode@gmail.com>
 * Date: 1/3/19
 * Time: 3:47 PM
 */

namespace HalcyonLaravel\Base\Tests\App\Repositories;

use HalcyonLaravel\Base\Repository\ObserverContract;


/**
 * Class DummyClassObserver
 *
 * @package DummyNameSpaceClass\Repositories\DummyPath\DummyClass
 */
class Observer extends ObserverContract
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
     * @param       $model
     * @param array $data
     *
     * @return mixed
     */
    public static function stored($model, array $data)
    {

        return $model;
    }

    /**
     * @param       $model
     * @param array $data
     *
     * @return mixed
     */
    public static function updating($model, array $data)
    {
        return $model;
    }

    /**
     * @param       $model
     * @param array $data
     * @param array $oldModel
     *
     * @return mixed
     */
    public static function updated($model, array $data, array $oldModel)
    {


        return $model;
    }

    /**
     * @param $model
     *
     * @return mixed
     */
    public static function deleting($model)
    {
        return $model;
    }

    /**
     * @param $model
     *
     * @return mixed
     */
    public static function deleted($model)
    {
        return $model;
    }

    /**
     * @param $model
     *
     * @return mixed
     */
    public static function restoring($model)
    {
        return $model;
    }

    /**
     * @param $model
     *
     * @return mixed
     */
    public static function restored($model)
    {
        return $model;
    }

    /**
     * @param $model
     *
     * @return mixed
     */
    public static function purging($model)
    {
        return $model;
    }

    /**
     * @param $model
     *
     * @return mixed
     */
    public static function purged($model)
    {
        return $model;
    }
}
