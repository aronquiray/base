<?php

namespace HalcyonLaravel\Base\Tests\Features;

use HalcyonLaravel\Base\Tests\TestCase;

class CrudsResourcesFeatureTest extends TestCase
{
    /**
     * @test
     */
    public function index()
    {
        $this->get(route('admin.page.index'))->assertStatus(200);
    }

    /**
     * @test
     */
    public function create()
    {
        $this->get(route('admin.page.create'))->assertStatus(200);
    }

    /**
     * @test
     */
    public function show()
    {
        $this->get(route('admin.page.show', $this->page))->assertStatus(200);
    }

    /**
     * @test
     */
    public function edit()
    {
        $this->get(route('admin.page.edit', $this->page))->assertStatus(200);
    }

    /**
     * @test
     */
    public function disabled()
    {
        $this->get(route('admin.page.status', 'disable'))->assertStatus(200);
    }

    /**
     * @test
     */
    public function deleted()
    {
        $this->get(route('admin.page-sd.deleted'))->assertStatus(200);
    }
}
