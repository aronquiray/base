<?php
/**
 * Created by PhpStorm.
 * User: Lloric Mayuga Garcia <lloricode@gmail.com>
 * Date: 3/3/19
 * Time: 6:28 PM
 */

namespace HalcyonLaravel\Base\Repository;

use Illuminate\Database\Eloquent\Model;
use Prettus\Repository\Contracts\CriteriaInterface;
use Prettus\Repository\Contracts\RepositoryInterface;

/**
 * Interface BaseRepositoryInterface
 *
 * @package HalcyonLaravel\Base\Repository
 * @method \Prettus\Repository\Eloquent\BaseRepository pushCriteria(CriteriaInterface $param)
 * @method Model makeModel()
 * @method model()
 */
interface BaseRepositoryInterface extends RepositoryInterface
{
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
    );

    /**
     * @param $id
     *
     * @return mixed
     * @throws \Prettus\Repository\Exceptions\RepositoryException
     * @throws \Throwable
     */
    public function purge($id);

    /**
     * @param $id
     *
     * @return mixed
     * @throws \Prettus\Repository\Exceptions\RepositoryException
     * @throws \Throwable
     */
    public function restore($id);
}