<?php

namespace HalcyonLaravel\Base\Tests;

use Illuminate\Database\Schema\Blueprint;
use App\Models\User;
use App\Models\Content;
use App\Models\Core\Page;
use App\Models\Core\PageSoftDelete;
use Orchestra\Testbench\TestCase as Orchestra;
use Route;
use View;

class TestCase extends Orchestra
{
    protected $user;
    protected $admin;
    protected $content;
    protected $page;
    
    public function setUp()
    {
        parent::setUp();
        $this->setUpDatabase($this->app);
        $this->setUpSeed();
        $this->setUpRoutes();
        View::addLocation(__DIR__.'/resources/views/');
    }


    public function tearDown()
    {
        parent::tearDown();
    }

    protected function setUpRoutes()
    {

        // just test
        Route::get('test', 'PageStatusController@status')->name('frontend.page.show');
        Route::get('test2', 'PageStatusController@status')->name('frontend.page-sd.show');

        Route::group([
            'namespace' => 'App\Http\Controllers\Backend\Core\Page',
            'prefix' => 'admin',
            'as' => 'admin.',
            // 'middleware' => 'admin'
        ], function () {
            Route::post('page/table', 'PagesTableController')->name('page.table');
            Route::get('page/status/{status}', 'PageStatusController@status')->name('page.status');
            Route::patch('page/status/{page}', 'PageStatusController@update')->name('page.status.update');
            Route::resource('page', 'PagesController');
        });


        // Softdelete
        Route::group([
            'namespace' => 'App\Http\Controllers\Backend',
            'prefix' => 'admin',
            'as' => 'admin.',
            // 'middleware' => 'admin'
        ], function () {
            Route::get('page-sd/deleted', 'Core\Page\PagesSoftDeleteController@deleted')->name('page-sd.deleted');
            Route::patch('page-sd/{page_sd}/deleted', 'Core\Page\PagesSoftDeleteController@restore')->name('page-sd.restore');
            Route::delete('page-sd/{page_sd}/deleted', 'Core\Page\PagesSoftDeleteController@purge')->name('page-sd.purge');
            Route::group([
                    'namespace'  => 'Core\Page',
                ], function () {
                    Route::post('page-sd/table', 'PagesSoftDeleteTableController')->name('page-sd.table');
                    Route::patch('page-sd/status/{page_sd}', 'PageStatusController@update')->name('page-sd.status.update');
                    Route::resource('page-sd', 'PagesSDController');
                });
        });

        $this->pageSoftdelete = PageSoftDelete::create([
            'title' => 'test me to delete',
            'status' => 'enable',
        ]);
        // Route::get('page', 'tt')->name('frontend.page.show');
    }

    protected function setUpSeed()
    {
        $this->admin = User::create([
            'first_name' => 'Istrator',
            'last_name' => 'Admin',
        ]);

        $this->user = User::create([
            'first_name' => 'Basic',
            'last_name' => 'User',
        ]);

        $this->content = Content::create([
            'name' => 'Content Name',
            'content' => 'Content content',
            'description' => 'Content content',
            'image' => 'http://test.com/me.png',
            'status' => 'active',
        ]);

        $this->page = Page::create([
            'title' => 'Title Name',
            'status' => 'enable',
        ]);
    }

    /**
     * Set up the database.
     *
     * @param \Illuminate\Foundation\Application $app
     */
    protected function setUpDatabase($app)
    {
        $this->app['db']->connection()->getSchemaBuilder()->create('pages_sd', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title')->unique();
            $table->string('url')->nullable();
            $table->string('type')->unique()->nullable();
            $table->string('template')->nullable();
            $table->text('description')->nullable();
            $table->enum('status', ['enable', 'disable']);
            $table->timestamps();
            $table->softDeletes();
        });

        // test Migrations
        $app['db']->connection()->getSchemaBuilder()->create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('first_name');
            $table->string('last_name');
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->timestamps();
        });



        $app['db']->connection()->getSchemaBuilder()->create('contents', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->unique();
            $table->text('content');
            $table->timestamps();
        });
        $app['db']->connection()->getSchemaBuilder()->create('pages', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title')->unique();
            $table->string('url')->nullable();
            $table->string('type')->unique()->nullable();
            $table->string('template')->nullable();
            $table->text('description')->nullable();
            $table->enum('status', ['enable', 'disable']);
            $table->timestamps();
        });
    }

    /**
     * Define environment setup.
     *
     * @param  \Illuminate\Foundation\Application  $app
     * @return void
     */
    protected function getEnvironmentSetUp($app)
    {
        // Setup default database to use sqlite :memory:
        $app['config']->set('database.default', 'testbench');
        $app['config']->set('database.connections.testbench', [
            'driver'   => 'sqlite',
            'database' => ':memory:',
            'prefix'   => '',
        ]);
    }


    protected function getPackageAliases($app)
    {
        return [
            "DataTables" => "Yajra\\DataTables\\Facades\\DataTables"
        ];
    }

    protected function getPackageProviders($app)
    {
        return [
            "HalcyonLaravel\\Base\\Providers\\BaseServiceProvider",

            // --
            "Yajra\\DataTables\\DataTablesServiceProvider",
        ];
    }
}
