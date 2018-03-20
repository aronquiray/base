<?php

namespace HalcyonLaravel\Base\Tests\Features;

use Illuminate\Database\Schema\Blueprint;
use HalcyonLaravel\Base\Tests\TestCase;
use App\Models\Core\PageSoftDelete;
use Route;



use HalcyonLaravel\Base\Events\BaseDeletingEvent;
use HalcyonLaravel\Base\Events\BaseDeletedEvent;

use HalcyonLaravel\Base\Events\BaseRestoringEvent;
use HalcyonLaravel\Base\Events\BaseRestoredEvent;

use HalcyonLaravel\Base\Events\BasePurgingEvent;
use HalcyonLaravel\Base\Events\BasePurgedEvent;

class TestSoftDelete extends TestCase
{
    protected $pageSoftdelete;

    public function setUp()
    {
        parent::setUp();
        $this->app['db']->connection()->getSchemaBuilder()->create('pages_sd', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title')->unique();
            $table->string('slug');
            $table->string('url')->nullable();
            $table->string('type')->unique()->nullable();
            $table->string('template')->nullable();
            $table->text('description')->nullable();
            $table->enum('status', ['enable', 'disabled']);
            $table->timestamps();
            $table->softDeletes();
        });
        Route::group([
            'namespace' => 'App\Http\Controllers\Backend',
            'prefix' => 'admin',
            'as' => 'admin.',
            // 'middleware' => 'admin'
        ], function () {
            Route::group([
                'namespace'  => 'Core\Page',
        ], function () {
            Route::resource('page-sd', 'PagesSDController');
        });
            Route::get('page-sd/deleted', 'Core\Page\PagesSoftDeleteController@deleted')->name('page-sd.deleted');
            Route::patch('page-sd/{page_sd}/deleted', 'Core\Page\PagesSoftDeleteController@restore')->name('page-sd.restore');
            Route::delete('page-sd/{page_sd}/deleted', 'Core\Page\PagesSoftDeleteController@purge')->name('page-sd.purge');
        });

        $this->pageSoftdelete = PageSoftDelete::create([
            'title' => 'test me to delete',
            'status' => 'enable',
        ]);
    }
    
       
    public function testLogDeleteOnSoftdelete()
    {
        $this->expectsEvents(BaseDeletingEvent::class);
        $this->expectsEvents(BaseDeletedEvent::class);
        
        $response = $this->withHeaders([
            'X-Header' => 'Value',
            ])->json('DELETE', route('admin.page-sd.destroy', $this->pageSoftdelete), []);
            
        $response
            ->assertStatus(302)
            ->assertSessionHas('flash_success', 'test me to delete has been deleted.')
            ->assertRedirect(route('admin.page-sd.deleted'));

        $this->assertSoftDeleted((new PageSoftDelete)->getTable(), ['id'=>$this->pageSoftdelete->id,]);
    }
       
    
       
    public function testLogRestoreOnSoftdelete()
    {
        $this->pageSoftdelete->deleted_at = now();
        $this->pageSoftdelete->save();
        $this->expectsEvents(BaseRestoringEvent::class);
        $this->expectsEvents(BaseRestoredEvent::class);
        
        $response = $this->withHeaders([
            'X-Header' => 'Value',
            ])->json('PATCH', route('admin.page-sd.restore', $this->pageSoftdelete), []);
     
        $response
            ->assertStatus(302)
            ->assertSessionHas('flash_success', 'test me to delete has been restored.')
            ->assertRedirect(route('admin.page-sd.index'));

        $this->assertDatabaseHas((new PageSoftDelete)->getTable(), ['id'=>$this->pageSoftdelete->id,'deleted_at'=>null]);
    }
       

    public function testLogPurgeOnSoftdelete()
    {
        $this->pageSoftdelete->deleted_at = now();
        $this->pageSoftdelete->save();
        $this->expectsEvents(BasePurgingEvent::class);
        $this->expectsEvents(BasePurgedEvent::class);
        
        $response = $this->withHeaders([
            'X-Header' => 'Value',
            ])->json('DELETE', route('admin.page-sd.purge', $this->pageSoftdelete), []);
            
        $response
            ->assertStatus(302);

        $this->assertDatabaseMissing((new PageSoftDelete)->getTable(), ['id'=>$this->pageSoftdelete->id,]);
    }
}
