<?php

namespace HalcyonLaravel\Base\Tests\Features;

use HalcyonLaravel\Base\Tests\TestCase;

class TestCrudsResourcesFeature extends TestCase
{
    public function testIndex()
    {
        $this->get(route('admin.page.index'))->assertStatus(200);
    }

    public function testCreate()
    {
        $this->get(route('admin.page.create'))->assertStatus(200);
    }

    public function testShow()
    {
        $this->get(route('admin.page.show', $this->page))->assertStatus(200);
    }

    public function testEdit()
    {
        $this->get(route('admin.page.edit', $this->page))->assertStatus(200);
    }

    public function testDisabled()
    {
        $this->get(route('admin.page.status', 'disable'))->assertStatus(200);
    }

    public function testDeleted()
    {
        $this->get(route('admin.page-sd.deleted'))->assertStatus(200);
    }
}
