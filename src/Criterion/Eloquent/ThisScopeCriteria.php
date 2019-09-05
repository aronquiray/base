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
     * @var array
     */
    private $parameters;

    public function __construct(string $scopeName, ...$parameters)
    {
        $this->scopeName = $scopeName;
        $this->parameters = $parameters;
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
        if (is_null($this->parameters)) {
            return $model->{$this->scopeName}();
        }
        return $model->{$this->scopeName}(...$this->parameters);
    }
}
