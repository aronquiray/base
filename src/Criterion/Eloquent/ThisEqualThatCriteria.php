<?php
/**
 * Created by PhpStorm.
 * User: lloric
 * Date: 3/12/19
 * Time: 11:49 AM
 */

namespace HalcyonLaravel\Base\Criterion\Eloquent;

use Prettus\Repository\Contracts\CriteriaInterface;
use Prettus\Repository\Contracts\RepositoryInterface;

/**
 * Class ThisEqualThatCriteria
 *
 * @package HalcyonLaravel\Base\Criterion\Eloquent
 * @codeCoverageIgnore
 */
class ThisEqualThatCriteria implements CriteriaInterface
{
    /**
     * @var string
     */
    private $field;

    /**
     * @var null
     */
    private $value;
    /**
     * @var string
     */
    private $comparison;

    /**
     * ThisEqualThatCriteria constructor.
     *
     * @param  string  $field
     * @param  null  $value
     * @param  string  $comparison
     */
    public function __construct(string $field, $value = null, string $comparison = '=')
    {
        $this->field = $field;
        $this->value = $value;
        $this->comparison = $comparison;
    }

    /**
     * @param                                                   $model
     * @param  \Prettus\Repository\Contracts\RepositoryInterface  $repository
     *
     * @return mixed
     */
    public function apply($model, RepositoryInterface $repository)
    {
        return $model->where($this->field, $this->comparison, $this->value);
    }

}