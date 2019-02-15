<?php
/**
 * Created by PhpStorm.
 * User: lloric
 * Date: 2/15/19
 * Time: 12:11 PM
 */

namespace HalcyonLaravel\Base\Models\Contracts;

interface BaseModelPermissionInterface
{
    /**
     * Return all the permissions for this model.
     *
     * @return array
     */
    public static function permissions(): array;

}