<?php
/**
 * Created by PhpStorm.
 * User: lloric
 * Date: 3/6/19
 * Time: 9:57 AM
 */

namespace HalcyonLaravel\Base\Tests\Units;

use HalcyonLaravel\Base\Tests\Models\Content;
use HalcyonLaravel\Base\Tests\Models\Core\Page;
use HalcyonLaravel\Base\Tests\TestCase;

class LinksTest extends TestCase
{
    /**
     * @test
     */
    public function invalidKeyOnLinks()
    {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Invalid attribute key [xx] on links.');
        $this->content->actions('backend', null, true);

    }

    /**
     * @test
     */
    public function getGroupOnlyLink()
    {

        $links = $this->page->actions('backend', null, true);
//        dd($links);
        $this->assertEquals($links, [
            'show' => route(Page::ROUTE_ADMIN_PATH . '.show', $this->page),
        ]);
    }

    /**
     * @test
     */
    public function getGroup_()
    {
        $links = $this->page->actions('backend');
//        dd(__METHOD__,$links);
        $this->assertEquals($links, [
            'show' => [
                'type' => 'show',
//                'permission' => true,
                'url' => 'http://localhost/admin/page/' . $this->page->id,
            ],
        ]);
    }

    /**
     * @test
     */
    public function invalidGroup()
    {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Invalid action group.');

        app(Content::class)->actions('xxx');
    }
}