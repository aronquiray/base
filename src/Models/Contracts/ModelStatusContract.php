<?php

namespace HalcyonLaravel\Base\Models\Contracts;

interface ModelStatusContract
{
    /**
     * Return the array of statuses.
     * ex. [ 0  => 'Disabled', 1 => 'Active' ], [ 'Disabled', 'Active'], [ 'disabled' => 'Disabled', 'active' => 'Active' ]  
     * 
     * @return array
     */
    public function statuses() : array;

    /**
     * Return the column for the status on this model.
     *
     * @return array
     */
    public function statusKeyName(): string;
}
