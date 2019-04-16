<?php

namespace HalcyonLaravel\Base\Tests\Units;

use HalcyonLaravel\Base\Tests\TestCase;

// use App\Models\Core\Page;

class TestBasable extends TestCase
{
    public function test_get_basable_value()
    {
        $basableName = $this->content->base('history_name');
        $this->assertEquals('Content Name', $basableName);
    }

    // public function testGetModel()
    // {

    // }
}
