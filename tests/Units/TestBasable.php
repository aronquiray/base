<?php

namespace HalcyonLaravel\Base\Tests\Units;

use  HalcyonLaravel\Base\Tests\TestCase;

class TestBasable extends TestCase
{
    public function testGetBasableValue()
    {
        $basebaleName = $this->content->base('history_name');
        $this->assertEquals('Content Name', $basebaleName);
    }
}
