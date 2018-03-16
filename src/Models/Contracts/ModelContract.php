<?php

namespace HalcyonLaravel\Base\Models\Contracts;

interface ModelContract
{
    /**
     * Return the links related to this model.
     *
     * @return array
     */
    public function links(): array;

    /**
     * Return the baseable configuration array for this model.
     *
     * @return array
     */
    public function baseable(): array;
}
