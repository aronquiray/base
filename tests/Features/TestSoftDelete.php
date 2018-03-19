<?php

namespace HalcyonLaravel\Base\Tests\Features;

use Illuminate\Database\Schema\Blueprint;
use HalcyonLaravel\Base\Tests\TestCase;
use App\Models\Core\PageSoftDelete;
use Route;


// use HalcyonLaravel\Base\Events\BaseStoringEvent;
// use HalcyonLaravel\Base\Events\BaseStoredEvent;

// use HalcyonLaravel\Base\Events\BaseUpdatingEvent;
// use HalcyonLaravel\Base\Events\BaseUpdatedEvent;

use HalcyonLaravel\Base\Events\BaseDeletingEvent;
use HalcyonLaravel\Base\Events\BaseDeletedEvent;

use HalcyonLaravel\Base\Events\BaseRestoringEvent;
use HalcyonLaravel\Base\Events\BaseRestoredEvent;

use HalcyonLaravel\Base\Events\BasePurgingEvent;
use HalcyonLaravel\Base\Events\BasePurgedEvent;

class TestSoftDelete extends TestCase
{
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
                            Route::resource('page-sd', 'PagesSoftDeleteController');
                        });
                            Route::get('page-sd/deleted', 'Core\Page\PagesSoftDeleteController@deleted')->name('page-sd.deleted');
                        });
    }
    
       
    public function testLogDeleteOnSoftdelete()
    {
        $page = PageSoftDelete::create([
                        'title' => 'test me to delete',
                        'status' => 'enable',
                ]);

        $this->expectsEvents(BaseDeletingEvent::class);
        $this->expectsEvents(BaseDeletedEvent::class);
        
        $response = $this->withHeaders([
                        'X-Header' => 'Value',
                        ])->json('DELETE', route('admin.page-sd.destroy', $page), []);
        
        $response
                        ->assertStatus(302)
                        ->assertSessionHas('flash_success', 'test me to delete has been deleted.')
                        ->assertRedirect(route('admin.page-sd.deleted'));

        $this->assertSoftDeleted((new PageSoftDelete)->getTable(), ['id'=>$page->id,]);
    }
}
