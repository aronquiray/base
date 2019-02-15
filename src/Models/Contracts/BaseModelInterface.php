<?php
/**
 * Created by PhpStorm.
 * User: Lloric Mayuga Garcia <lloricode@gmail.com>
 * Date: 12/10/18
 * Time: 9:51 AM
 */

namespace HalcyonLaravel\Base\Models\Contracts;


interface BaseModelInterface
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