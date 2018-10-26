<?php

namespace HalcyonLaravel\Base\Tests\Features;

use HalcyonLaravel\Base\Tests\TestCase;

class TestCrudsResourcesFeature extends TestCase
{
    public function testIndex()
    {
        $this->json('GET', route('admin.page.index'))->assertStatus(200);
    }

    public function testCreate()
    {
        $this->json('GET', route('admin.page.create'))->assertStatus(200);
    }

    public function testShow()
    {
        $this->json('GET', route('admin.page.show', $this->page))->assertStatus(200);
    }

    public function testEdit()
    {
        $this->json('GET', route('admin.page.edit', $this->page))->assertStatus(200);
    }

    public function testDisabled()
    {
        $this->json('GET', route('admin.page.status', 'disable'))->assertStatus(200);
    }

    public function testDeleted()
    {
        $this->json('GET', route('admin.page-sd.deleted'))->assertStatus(200);
    }
}
