<?php

namespace HalcyonLaravel\Base\Tests\Units;

use HalcyonLaravel\Base\Tests\TestCase;

class ModelFunctionTest extends TestCase
{
    /**
     * @test
     */
    public function get_links()
    {
        $links = $this->page->actions('backend', ['show'], true);

        $expected = [
            "show" => 'http://localhost/admin/page/'.$this->page->id,
        ];

        $this->assertEquals($expected, $links);
    }

    /**
     * @test
     */
    public function get_link()
    {
        $link = $this->page->actions('backend', 'show', true);

        $expected = 'http://localhost/admin/page/'.$this->page->id;

        $this->assertEquals($expected, $link);
    }
}
