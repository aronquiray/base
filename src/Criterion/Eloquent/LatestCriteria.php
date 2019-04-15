<?php
/**
 * Created by PhpStorm.
 * User: Lloric Mayuga Garcia <lloricode@gmail.com>
 * Date: 12/21/18
 * Time: 8:42 AM
 */

namespace HalcyonLaravel\Base\Criterion\Eloquent;

use Prettus\Repository\Contracts\CriteriaInterface;
use Prettus\Repository\Contracts\RepositoryInterface;

/**
 * Class LatestCriteria
 *
 * @package HalcyonLaravel\Base\Criterion\Eloquent
 * @codeCoverageIgnore
 */
class LatestCriteria implements CriteriaInterface
{
    private $column;

    /**
     * LatestCriteria constructor.
     *
     * @param  string  $column
     */
    public function __construct(string $column = 'updated_at')
    {
        $this->column = $column;
    }

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
        return $model->latest($this->column);
    }
}