<?php

namespace HalcyonLaravel\Base\Tests\Features;

use HalcyonLaravel\Base\Tests\TestCase;

class TestBaseController extends TestCase
{
    /**
     * @test
     */
    public function show_404()
    {
        $this->get(route('admin.page.show', 'im-not-really-exist'))
            ->assertStatus(404);
    }
}