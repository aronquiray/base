<?php

namespace HalcyonLaravel\Base\Tests\Units;

use HalcyonLaravel\Base\Tests\TestCase;

class TestModelFunctions extends TestCase
{
    public function testGetLinks()
    {
        $links = $this->page->actions('backend', ['show'], true);

        $expected = [
            "show" => 'http://localhost/admin/page/' . $this->page->id,
        ];

        $this->assertEquals($expected, $links);
    }

    public function testGetLink()
    {
        $link = $this->page->actions('backend', 'show', true);

        $expected = 'http://localhost/admin/page/' . $this->page->id;

        $this->assertEquals($expected, $link);
    }
}
