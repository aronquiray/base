<?php

namespace HalcyonLaravel\Base\Tests\Units;

use HalcyonLaravel\Base\Tests\TestCase;
use App\Models\Core\Page;

class TestModelFunctions extends TestCase
{
    public function testGetLinks()
    {
        $links = (new  Page)->actions('backend', ['show'], true);

        $expected = [
            "show" => "http://localhost/admin/page"
        ];

        $this->assertEquals($expected, $links);
    }
    
    public function testGetLink()
    {
        $link =  (new  Page)->actions('backend', 'show', true);

        $expected = "http://localhost/admin/page";

        $this->assertEquals($expected, $link);
    }
}