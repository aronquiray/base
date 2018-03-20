<?php

namespace HalcyonLaravel\Base\Controllers\Backend\Contract;

interface StatusContract
{
    /**
     * Return the links related to this model.
     *
     * @return array
     */
    public function statusKeyName(): string;

    /**
     * Return the bool if status is Active.
     *
     * @return array
     */
    public function statusIsActive($model) : bool; // TODO: add dependency injecttion

    /**
     * Return the string label if model is Active.
     *
     * @return array
     */
    public function statusActiveLabel() : string;

    /**
     * Return the string label if model is Inactive.
     *
     * @return array
     */
    public function statusInactiveLabel() : string;
}
