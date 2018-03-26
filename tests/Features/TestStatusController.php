<?php

namespace HalcyonLaravel\Base\Tests\Features;

use HalcyonLaravel\Base\Tests\TestCase;
use App\Models\Core\Page;

class TestStatusController extends TestCase
{
    // public function setUp()
    // {
    //     parent::setUp();
        
    //     $this->Artisan('route:list');
    //     dd(\Artisan::output());
    // }
    
    public function testUpdateStatusToEnable()
    {
        $this->page->status = 'disable';
        $this->page->save();

        $response = $this->json('PATCH', route('admin.page.status', $this->page), ['status' => 'enable']);

        $response->assertStatus(302);
        $this->assertDatabaseHas((new Page)->getTable(), [
            'id' => $this->page->id,
            'status' => 'enable'
        ]);
    }
    
    public function testUpdateStatusToDisable()
    {
        // just to make sure
        $this->page->status = 'enable';
        $this->page->save();

        $response = $this->json('PATCH', route('admin.page.status', $this->page), ['status' => 'disable']);

        $response->assertStatus(302);
        $this->assertDatabaseHas((new Page)->getTable(), [
            'id' => $this->page->id,
            'status' => 'disable'
        ]);
    }

        
    public function testStatusRequiredException()
    {
        $response = $this->json('PATCH', route('admin.page.status', $this->page));

        $response
            ->assertStatus(403)
            ->assertJson([
                'message'=>'The status is required.'
        ]);
    }
}
