<?php

namespace HalcyonLaravel\Base\Tests\Features;

use HalcyonLaravel\Base\Tests\TestCase;
use App\Models\Core\Page;

class TestDataTable extends TestCase
{
    public function setUp()
    {
        parent::setUp();
        $this->actingAs($this->admin);
    }
    
    public function testNoData()
    {
        Page::truncate();
        $response = $this->json('POST', route('admin.page.table'), []);

        $response
                ->assertStatus(200)
                ->assertJson([
                "draw"=> 0,
                        "recordsTotal"=> 0,
                        "recordsFiltered"=> 0,
                        "data"=> []
                ]);
    }
}
