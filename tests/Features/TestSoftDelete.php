<?php

namespace HalcyonLaravel\Base\Tests\Features;

use HalcyonLaravel\Base\Tests\Models\Core\PageSoftDelete;
use HalcyonLaravel\Base\Tests\TestCase;
use Route;

class TestSoftDelete extends TestCase
{
    protected $pageSoftdelete;

    public function setUp()
    {
        parent::setUp();
    }

    public function testLogDeleteOnSoftdelete()
    {
        $response = $this->delete(route('admin.page-sd.destroy', $this->pageSoftdelete), []);

        $response->assertStatus(302)->assertSessionHas('flash_success', 'test me to delete has been deleted.')->assertRedirect(route('admin.page-sd.deleted'));

        $this->assertSoftDeleted((new PageSoftDelete)->getTable(), ['id' => $this->pageSoftdelete->id,]);
    }

    public function testLogRestoreOnSoftdelete()
    {
        $this->pageSoftdelete->deleted_at = now();
        $this->pageSoftdelete->save();

        $response = $this->patch(route('admin.page-sd.restore', $this->pageSoftdelete), []);

        $response->assertStatus(302)->assertSessionHas('flash_success', 'test me to delete has been restored.')->assertRedirect(route('admin.page-sd.index'));

        $this->assertDatabaseHas((new PageSoftDelete)->getTable(), [
            'id' => $this->pageSoftdelete->id,
            'deleted_at' => null,
        ]);
    }

    public function testLogPurgeOnSoftdelete()
    {
        $this->pageSoftdelete->deleted_at = now();
        $this->pageSoftdelete->save();

        $response = $this->delete(route('admin.page-sd.purge', $this->pageSoftdelete), []);

        $response->assertStatus(302);

        $this->assertDatabaseMissing((new PageSoftDelete)->getTable(), ['id' => $this->pageSoftdelete->id,]);
    }

    public function testOnPurgeOnNotDeleted()
    {
        // $this->expectsEvents(BasePurgingEvent::class);
        // $this->expectsEvents(BasePurgedEvent::class);
        //$this->expectException(RepositoryException::class);

        $response = $this->delete(route('admin.page-sd.purge', $this->pageSoftdelete));

        $response->assertStatus(404);


        $this->assertDatabaseHas((new PageSoftDelete)->getTable(), ['id' => $this->pageSoftdelete->id,]);
    }

    public function testOnRestoreOnNotDeleted()
    {
        $response = $this->patch(route('admin.page-sd.restore', $this->pageSoftdelete), []);

        $response->assertStatus(404);

        //$response->assertJson([
        //    'message' => 'This content has not been deleted yet.',
        //]);

        $this->assertDatabaseHas((new PageSoftDelete)->getTable(), [
            'id' => $this->pageSoftdelete->id,
            'deleted_at' => null,
        ]);
    }
}
