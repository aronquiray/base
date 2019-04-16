<?php

namespace HalcyonLaravel\Base\Tests\Features;

use HalcyonLaravel\Base\Tests\TestCase;

class TestCrudsResourcesFeature extends TestCase
{
    public function test_index()
    {
        $this->get(route('admin.page.index'))->assertStatus(200);
    }

    public function test_create()
    {
        $this->get(route('admin.page.create'))->assertStatus(200);
    }

    public function test_show()
    {
        $this->get(route('admin.page.show', $this->page))->assertStatus(200);
    }

    public function test_edit()
    {
        $this->get(route('admin.page.edit', $this->page))->assertStatus(200);
    }

    public function test_disabled()
    {
        $this->get(route('admin.page.status', 'disable'))->assertStatus(200);
    }

    public function test_deleted()
    {
        $this->get(route('admin.page-sd.deleted'))->assertStatus(200);
    }
}
