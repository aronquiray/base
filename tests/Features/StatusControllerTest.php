<?php

namespace HalcyonLaravel\Base\Tests\Features;

use HalcyonLaravel\Base\Tests\Models\Core\Page;
use HalcyonLaravel\Base\Tests\TestCase;

class StatusControllerTest extends TestCase
{
    // public function setUp()
    // {
    //     parent::setUp();

    //     $this->Artisan('route:list');
    //     dd(\Artisan::output());
    // }
    /**
     * @test
     */
    public function update_status_to_enable()
    {
        $this->page->status = 'disable';
        $this->page->save();

        $response = $this->patch(route('admin.page.status', $this->page), ['status' => 'enable']);

        $response->assertStatus(302);
        $this->assertDatabaseHas(app(Page::class)->getTable(), [
            'id' => $this->page->id,
            'status' => 'enable',
        ]);
    }

    /**
     * @test
     */
    public function update_status_to_disable()
    {
        // just to make sure
        $this->page->status = 'enable';
        $this->page->save();

        $response = $this->patch(route('admin.page.status', $this->page), ['status' => 'disable']);

        $response->assertStatus(302);
        $this->assertDatabaseHas(app(Page::class)->getTable(), [
            'id' => $this->page->id,
            'status' => 'disable',
        ]);
    }

    /**
     * @test
     */
    public function status_required_exception()
    {
        $response = $this->patch(route('admin.page.status', $this->page));

        $response->assertStatus(403);
        //->assertJson([
        //    'message' => 'The status is required.',
        //]);
    }
}
