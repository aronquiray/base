<?php

namespace HalcyonLaravel\Base\Criterion\Eloquent;

use Prettus\Repository\Contracts\CriteriaInterface;
use Prettus\Repository\Contracts\RepositoryInterface;

class ThisScopeCriteria implements CriteriaInterface
{
    /**
     * @var string
     */
    private $scopeName;

    /**
     * @var null
     */
    private $parameter;

    public function __construct(string $scopeName, $parameter = null)
    {
        $this->scopeName = $scopeName;
        $this->parameter = $parameter;
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
        if (is_null($this->parameter)) {
            return $model->{$this->scopeName}();
        }
        return $model->{$this->scopeName}($this->parameter);
    }
}