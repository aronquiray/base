<?php

namespace HalcyonLaravel\Base\Tests\Features;

use  HalcyonLaravel\Base\Tests\TestCase;

class TestEventFeature extends TestCase
{
    public function testLogStore()
    {
        $response = $this->withHeaders([
            'X-Header' => 'Value',
        ])->json('POST', route('admin.page.store'), [
            'title' => 'Salliess',
        ]);


        dd($response);
        $response
            ->assertStatus(200);
        // ->assertJson([
            //     'created' => true,
            // ]);
    }
}
