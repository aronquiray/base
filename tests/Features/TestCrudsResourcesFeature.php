<?php

namespace HalcyonLaravel\Base\Tests\Features;

use HalcyonLaravel\Base\Tests\TestCase;
use App\Models\Core\Page;

class TestCrudsResourcesFeature extends TestCase
{
    public function testIndex()
    {
        $response = $this->json('GET', route('admin.page.index'));

        $response
                ->assertStatus(200);
    }
    
    public function testCreate()
    {
        $response = $this->json('GET', route('admin.page.create'));

        $response
                ->assertStatus(200);
    }
    
    public function testShow()
    {
        $response = $this->json('GET', route('admin.page.show', $this->page));

        $response
                ->assertStatus(200);
    }

    public function testEdit()
    {
        $response = $this->json('GET', route('admin.page.edit', $this->page));

        $response
                ->assertStatus(200);
    }

    public function testDisabled()
    {
        $response = $this->json('GET', route('admin.page.disabled'));

        $response
                ->assertStatus(200);
    }

//     public function testDeleted()
//     {
//         // $this->artisan('route:list');
//         // dd(\Artisan::output());
//         $response = $this->json('GET', route('admin.page-sd.deleted'));
// // dd($response);
//         $response
//                 ->assertStatus(200);
//     }
}
