<?php
/**
 * Created by PhpStorm.
 * User: lloric
 * Date: 2/15/19
 * Time: 11:30 AM
 */

namespace HalcyonLaravel\Base\Tests\Units;

use HalcyonLaravel\Base\Tests\Models\TestMissingConstant;
use HalcyonLaravel\Base\Tests\TestCase;
use InvalidArgumentException;

class MissingModelConstantTest extends TestCase
{
    /**
     * @test
     */
    public function missingConstant()
    {
        $this->expectException(InvalidArgumentException::class);
        app(TestMissingConstant::class);
    }
}