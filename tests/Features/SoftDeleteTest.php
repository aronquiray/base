<?php

namespace HalcyonLaravel\Base\Tests\Features;

use HalcyonLaravel\Base\Tests\Models\Core\PageSoftDelete;
use HalcyonLaravel\Base\Tests\TestCase;
use Route;

class SoftDeleteTest extends TestCase
{
    protected $pageSoftdelete;

    public function setUp(): void
    {
        parent::setUp();
    }

    /**
     * @test
     * @throws \ReflectionException
     */
    public function log_delete_on_soft_delete()
    {
        $response = $this->delete(route('admin.page-sd.destroy', $this->pageSoftdelete), []);

        $response->assertStatus(302)->assertSessionHas('flash_success',
            'test me to delete has been deleted.')->assertRedirect(route('admin.page-sd.deleted'));

        $this->assertSoftDeleted((new PageSoftDelete)->getTable(), ['id' => $this->pageSoftdelete->id,]);
    }

    /**
     * @test
     * @throws \ReflectionException
     */
    public function log_restore_on_soft_delete()
    {
        $this->pageSoftdelete->deleted_at = now();
        $this->pageSoftdelete->save();

        $response = $this->patch(route('admin.page-sd.restore', $this->pageSoftdelete), []);

        $response->assertStatus(302)->assertSessionHas('flash_success',
            'test me to delete has been restored.')->assertRedirect(route('admin.page-sd.index'));

        $this->assertDatabaseHas((new PageSoftDelete)->getTable(), [
            'id' => $this->pageSoftdelete->id,
            'deleted_at' => null,
        ]);
    }

    /**
     * @test
     * @throws \ReflectionException
     */
    public function log_purge_on_soft_delete()
    {
        $this->pageSoftdelete->deleted_at = now();
        $this->pageSoftdelete->save();

        $response = $this->delete(route('admin.page-sd.purge', $this->pageSoftdelete), []);

        $response->assertStatus(302);

        $this->assertDatabaseMissing((new PageSoftDelete)->getTable(), ['id' => $this->pageSoftdelete->id,]);
    }

    /**
     * @test
     * @throws \ReflectionException
     */
    public function on_purge_on_not_deleted()
    {
        // $this->expectsEvents(BasePurgingEvent::class);
        // $this->expectsEvents(BasePurgedEvent::class);
        //$this->expectException(RepositoryException::class);

        $response = $this->delete(route('admin.page-sd.purge', $this->pageSoftdelete));

        $response->assertStatus(404);


        $this->assertDatabaseHas((new PageSoftDelete)->getTable(), ['id' => $this->pageSoftdelete->id,]);
    }

    /**
     * @test
     * @throws \ReflectionException
     */
    public function on_restore_on_not_deleted()
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
