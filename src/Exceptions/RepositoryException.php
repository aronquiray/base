<?php

namespace HalcyonLaravel\Base\Exceptions;

use Symfony\Component\HttpKernel\Exception\HttpException;

class RepositoryException extends HttpException
{
    //public static function notDeleted(): self
    //{
    //    return new static(403, trans('base::exceptions.not_deleted'));
    //}
}
