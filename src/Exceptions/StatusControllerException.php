<?php

namespace HalcyonLaravel\Base\Exceptions;

use Symfony\Component\HttpKernel\Exception\HttpException;

/**
 * Class StatusControllerException
 *
 * @package HalcyonLaravel\Base\Exceptions
 */
class StatusControllerException extends HttpException
{
    //public static function required(): self
    //{
    //    return new static(403, trans('base::exceptions.status_required'));
    //}
}
