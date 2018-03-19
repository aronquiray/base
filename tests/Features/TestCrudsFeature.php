<?php

namespace HalcyonLaravel\Base\Tests\Features;

use HalcyonLaravel\Base\Tests\TestCase;
use App\Models\Core\Page;

class TestCrudsFeature extends TestCase
{
    public function testLogStore()
    {
        $response = $this->withHeaders([
            'X-Header' => 'Value',
        ])->json('POST', route('admin.page.store'), [
            'title' => 'Salliess',
            'description' => 'description test',
            'status' => 'enable',
        ]);
        
        $response
            ->assertStatus(302)
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

        $response = $this->withHeaders([
            'X-Header' => 'Value',
        ])->json('PUT', route('admin.page.update', $this->page), $dataNew);
        
        $response
            ->assertStatus(302)
            ->assertSessionHas('flash_success', 'new test title has been updated.')
            ->assertRedirect(route('admin.page.show',  Page::find(1)));

        $this->assertDatabaseHas((new Page)->getTable(), $dataNew);
    }
}
