<?php
/**
 * Created by PhpStorm.
 * User: lloric
 * Date: 2/15/19
 * Time: 11:18 AM
 */

namespace HalcyonLaravel\Base;

use InvalidArgumentException;
use ReflectionClass;

/**
 * Class Enforcer
 *
 * @package HalcyonLaravel\Base
 */
class Enforcer
{
    /**
     * @param $class
     * @param $c
     *
     * @throws \ReflectionException
     * @reference https://stackoverflow.com/questions/10368620/abstract-constants-in-php-force-a-child-class-to-define-a-constant
     */
    public static function __add($class, $c)
    {
        $reflection = new ReflectionClass($class);
        $constantsForced = $reflection->getConstants();
        foreach ($constantsForced as $constant => $value) {
            if (constant("$c::$constant") == "abstract") {
                throw new InvalidArgumentException("Undefined constant $c::$constant");
            }
        }
    }
}