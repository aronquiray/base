<?php

namespace HalcyonLaravel\Base\Tests\Features;

use HalcyonLaravel\Base\Tests\TestCase;
use App\Models\Core\Page;



use HalcyonLaravel\Base\Events\BaseStoringEvent;
use HalcyonLaravel\Base\Events\BaseStoredEvent;

use HalcyonLaravel\Base\Events\BaseUpdatingEvent;
use HalcyonLaravel\Base\Events\BaseUpdatedEvent;

use HalcyonLaravel\Base\Events\BaseDeletingEvent;
use HalcyonLaravel\Base\Events\BaseDeletedEvent;

use HalcyonLaravel\Base\Events\BaseRestoringEvent;
use HalcyonLaravel\Base\Events\BaseRestoredEvent;

use HalcyonLaravel\Base\Events\BasePurgingEvent;
use HalcyonLaravel\Base\Events\BasePurgedEvent;

// use Mockery as m;
// use Event;

class TestCrudsFeature extends TestCase
{
    public function testLogStore()
    {
        $this->expectsEvents(BaseStoringEvent::class);
        $this->expectsEvents(BaseStoredEvent::class);

        // Event::shouldReceive('fire')->with(m::on(function($event){
        //     return false;
        // }));

        // Event::shouldIgnoreMissing();

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


        $this->expectsEvents(BaseUpdatingEvent::class);
        $this->expectsEvents(BaseUpdatedEvent::class);

        $response = $this->withHeaders([
            'X-Header' => 'Value',
        ])->json('PUT', route('admin.page.update', $this->page), $dataNew);
        
        $response
            ->assertStatus(302)
            ->assertSessionHas('flash_success', 'new test title has been updated.')
            ->assertRedirect(route('admin.page.show', Page::find(1)));

        $this->assertDatabaseHas((new Page)->getTable(), $dataNew);
    }
}
