<?php

namespace HalcyonLaravel\Base\Exceptions;

use Symfony\Component\HttpKernel\Exception\HttpException;

/**
 * Class RepositoryException
 *
 * @package HalcyonLaravel\Base\Exceptions
 */
class RepositoryException extends HttpException
{
    //public static function notDeleted(): self
    //{
    //    return new static(403, trans('base::exceptions.not_deleted'));
    //}
}
