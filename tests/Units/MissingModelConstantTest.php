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
use Symfony\Component\HttpKernel\Exception\HttpException;

class MissingModelConstantTest extends TestCase
{
    /**
     * @test
     */
    public function missingConstant()
    {
        $this->expectException(HttpException::class);
        app(TestMissingConstant::class);
    }
}