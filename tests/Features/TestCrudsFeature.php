<?php

namespace HalcyonLaravel\Base\Tests\Features;

use HalcyonLaravel\Base\Tests\Models\Core\Page;
use HalcyonLaravel\Base\Tests\TestCase;

class TestCrudsFeature extends TestCase
{
    public function testLogStore()
    {

        // Event::shouldReceive('fire')->with(m::on(function($event){
        //     return false;
        // }));

        // Event::shouldIgnoreMissing();

        $response = $this->post(route('admin.page.store'), [
            'title' => 'Salliess',
            'description' => 'description test',
            'status' => 'enable',
        ]);

        $response->assertStatus(302)
            ->assertSessionHas('flash_success', 'Salliess has been created.')
            ->assertRedirect(route('admin.page.show', Page::find(2)));

        $this->assertDatabaseHas((new Page)->getTable(), [
            'title' => 'Salliess',
            'description' => 'description test',
            'status' => 'enable',
        ]);
    }

    public function testLogUpdate()
    {
        $dataNew = [
            'title' => 'new test title',
            'description' => 'new description test',
            'status' => 'enable',
        ];

        $response = $this->put(route('admin.page.update', $this->page), $dataNew);

        $response->assertStatus(302)->assertSessionHas('flash_success',
            'new test title has been updated.')->assertRedirect(route('admin.page.show', Page::find(1)));

        $this->assertDatabaseHas((new Page)->getTable(), $dataNew);
    }

    public function testLogDeleteOnNOTSoftdelete()
    {
        $response = $this->delete(route('admin.page.destroy', $this->page), []);

        $response->assertStatus(302)->assertSessionHas('flash_success',
            'Title Name has been deleted.')->assertRedirect(route('admin.page.index'));

        $this->assertDatabaseMissing((new Page)->getTable(), ['id' => 1]);
    }

    public function testLogStoreWithCustomeRedirecttion()
    {
        $customeRedirection = 'http://test-url.com/';

        $response = $this->post(route('admin.page.store'), [
            'title' => 'Salliess',
            'description' => 'description test',
            'status' => 'enable',
            '_submission' => $customeRedirection,
        ]);

        $response->assertStatus(302)->assertSessionHas('flash_success',
            'Salliess has been created.')->assertRedirect($customeRedirection);

        $this->assertDatabaseHas((new Page)->getTable(), [
            'title' => 'Salliess',
            'description' => 'description test',
            'status' => 'enable',
        ]);
    }
}
