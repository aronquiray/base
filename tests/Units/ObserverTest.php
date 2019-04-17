<?php

namespace HalcyonLaravel\Base\Tests\Units;

use HalcyonLaravel\Base\Tests\App\Repositories\Observer;
use HalcyonLaravel\Base\Tests\Models\Core\Page;
use HalcyonLaravel\Base\Tests\TestCase;
use InvalidArgumentException;

class ObserverTest extends TestCase
{
    /**
     * @test
     */
    public function check_before_delete_invalid_argument()
    {
        $this->expectException(InvalidArgumentException::class);
        $observer = new class extends Observer
        {
            public function test()
            {
                parent::checkBeforeDelete('im-invalid', Page::first(), function () {

                });
            }
        };
        $observer->test();
    }
}