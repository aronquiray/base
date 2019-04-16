<?php

namespace HalcyonLaravel\Base\Tests\Features;

use HalcyonLaravel\Base\Tests\TestCase;

class BaseControllerTest extends TestCase
{

    /**
     * @test
     */
    public function show_404()
    {
        $this->get(route('admin.page.show', 'im-not-really-exist'))
            ->assertStatus(404);
    }

    /**
     * @test
     */
    public function status_show_404()
    {
        $this->get(route('admin.page.status', 'im-not-really-exist'))
            ->assertStatus(404);
    }

    /**
     * @test
     */
    public function status_invalid_model()
    {
//        $this->expectExceptionMessage('Model must implemented in '.ModelStatusContract::class);
        $this->get(route('admin.content.status', 'im-not-really-exist'))
            ->assertStatus(500);
    }
}