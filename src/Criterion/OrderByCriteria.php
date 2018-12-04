<?php
/**
 * Created by PhpStorm.
 * User: Lloric Mayuga Garcia <lloricode@gmail.com>
 * Date: 12/4/18
 * Time: 9:07 AM
 */

namespace HalcyonLaravel\Base\Criterion;


use Prettus\Repository\Contracts\CriteriaInterface;
use Prettus\Repository\Contracts\RepositoryInterface;
use Schema;

class OrderByCriteria implements CriteriaInterface
{

    /**
     * Apply criteria in query repository
     *
     * @param                     $model
     * @param RepositoryInterface $repository
     *
     * @return mixed
     */
    public function apply($model, RepositoryInterface $repository)
    {
        $tableName = $model->getTable();
        if (Schema::hasColumn($tableName, 'updated_at')) {
            return $model->latest('updated_at');
        } elseif (Schema::hasColumn($tableName, 'created_at')) {
            return $model->latest('created_at');
        }
    }
}