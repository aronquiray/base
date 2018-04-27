<?php

namespace HalcyonLaravel\Base\Tests\Units;

use  HalcyonLaravel\Base\Tests\TestCase;

// use App\Models\Core\Page;

class TestBasable extends TestCase
{
    public function testGetBasableValue()
    {
        $basebaleName = $this->content->base('history_name');
        $this->assertEquals('Content Name', $basebaleName);
    }

    // public function testGetModel()
    // {

    // }
}
