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
    
    public function testUpdateStatusToDisable()
    {
        $this->page->status = 'disabled';
        $this->page->save();

        $response = $this->json('PATCH', route('admin.page.status', $this->page));

        $response->assertStatus(302);
        $this->assertDatabaseHas((new Page)->getTable(), [
            'id' => $this->page->id,
            'status' => 'enable'
        ]);
    }
    
    public function testUpdateStatusToEnable()
    {
        // just to make sure
        $this->page->status = 'enable';
        $this->page->save();

        $response = $this->json('PATCH', route('admin.page.status', $this->page));
        // dd($response);
        $response->assertStatus(302);
        $this->assertDatabaseHas((new Page)->getTable(), [
            'id' => $this->page->id,
            'status' => 'disabled'
        ]);
    }
}
