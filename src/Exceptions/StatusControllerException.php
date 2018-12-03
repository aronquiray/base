<?php

namespace HalcyonLaravel\Base\Exceptions;

use Symfony\Component\HttpKernel\Exception\HttpException;

class StatusControllerException extends HttpException
{
    //public static function required(): self
    //{
    //    return new static(403, trans('base::exceptions.status_required'));
    //}
}
