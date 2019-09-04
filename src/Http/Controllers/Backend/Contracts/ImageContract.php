<?php

namespace HalcyonLaravel\Base\Http\Controllers\Backend\Contracts;

interface ImageContract
{
    /**
     * @return array
     */
    public function models(): array;

    /**
     * @return array
     */
    public function noneRequiredModels(): array;

    /**
     * @return array
     */
    public function validations(): array;

}
