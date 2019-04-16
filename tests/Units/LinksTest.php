<?php
/**
 * Created by PhpStorm.
 * User: lloric
 * Date: 3/6/19
 * Time: 9:57 AM
 */

namespace HalcyonLaravel\Base\Tests\Units;

use Exception;
use HalcyonLaravel\Base\Tests\Models\Content;
use HalcyonLaravel\Base\Tests\Models\Core\Page;
use HalcyonLaravel\Base\Tests\TestCase;

class LinksTest extends TestCase
{
    /**
     * @test
     */
    public function invalid_key_on_links()
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Invalid attribute key [xx] on '.Content::class.'::links().');
        $this->content->actions('backend', null, true);

    }

    /**
     * @test
     */
    public function get_group_only_link()
    {

        $links = $this->page->actions('backend', ['show'], true);
//        dd($links);
        $this->assertEquals($links, [
            'show' => route(Page::ROUTE_ADMIN_PATH.'.show', $this->page),
        ]);
    }

    /**
     * @test
     */
    public function get_group_()
    {
        $links = $this->page->actions('backend');
//        dd(__METHOD__,$links);
        $this->assertEquals($links, [
            'show' => [
                'type' => 'show',
                'url' => 'http://localhost/admin/page/'.$this->page->id,
            ],
            'destroy' => [
                'type' => 'destroy',
                'url' => 'http://localhost/admin/page/'.$this->page->id,
                'redirect' => 'http://localhost/admin/page',
            ],
        ]);
    }

    /**
     * @test
     */
    public function invalid_group()
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Invalid action group.');

        app(Content::class)->actions('xxx');
    }
}