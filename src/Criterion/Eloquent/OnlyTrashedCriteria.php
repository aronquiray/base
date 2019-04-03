<?php
/**
 * Created by PhpStorm.
 * User: Lloric Mayuga Garcia <lloricode@gmail.com>
 * Date: 12/21/18
 * Time: 8:51 AM
 */

namespace HalcyonLaravel\Base\Criterion\Eloquent;

use Prettus\Repository\Contracts\CriteriaInterface;
use Prettus\Repository\Contracts\RepositoryInterface;

/**
 * Class OnlyTrashedCriteria
 *
 * @package HalcyonLaravel\Base\Criterion\Eloquent
 */
class OnlyTrashedCriteria implements CriteriaInterface
{

    /**
     * Apply criteria in query repository
     *
     * @param                     $model
     * @param  RepositoryInterface  $repository
     *
     * @return mixed
     */
    public function apply($model, RepositoryInterface $repository)
    {
        return $model->onlyTrashed();
    }
}