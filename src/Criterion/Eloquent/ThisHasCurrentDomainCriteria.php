<?php

namespace HalcyonLaravel\Base\Criterion\Eloquent;

use Prettus\Repository\Contracts\CriteriaInterface;
use Prettus\Repository\Contracts\RepositoryInterface;

class ThisHasCurrentDomainCriteria implements CriteriaInterface
{
    protected $machineName;

    public function __construct(string $machineName = null)
    {
        $this->machineName = $machineName;
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
        // see App\Models\Traits\HasDomains
        return $model->hasCurrentDomain($this->machineName);
    }
}