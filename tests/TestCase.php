<?php

namespace HalcyonLaravel\Base\Tests;

use CreatePermissionTables;
use HalcyonLaravel\Base\Tests\Models\Content;
use HalcyonLaravel\Base\Tests\Models\Core\Page;
use HalcyonLaravel\Base\Tests\Models\Core\PageSoftDelete;
use HalcyonLaravel\Base\Tests\Models\User;
use HalcyonLaravel\Base\Tests\Repositories\PageDeleteRepository;
use HalcyonLaravel\Base\Tests\Repositories\PageDeleteRepositoryEloquent;
use HalcyonLaravel\Base\Tests\Repositories\PageObserverRepository;
use HalcyonLaravel\Base\Tests\Repositories\PageObserverRepositoryEloquent;
use HalcyonLaravel\Base\Tests\Repositories\PageRepository;
use HalcyonLaravel\Base\Tests\Repositories\PageRepositoryEloquent;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\View;
use Orchestra\Testbench\TestCase as Orchestra;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class TestCase extends Orchestra
{
    protected $user;

    protected $admin;

    protected $content;

    protected $page;

    protected $pageSoftdelete;

    public function setUp(): void
    {
        parent::setUp();
        $this->setUpDatabase($this->app);
        $this->setUpSeed();
        $this->setUpRoutes();
        $this->bindingRepositories($this->app);
        View::addLocation(__DIR__.'/resources/views/');

        config([
            'halcyon-laravel.base' => [
                'responseBaseableName' => 'responseName',
            ],
        ]);
    }

    /**
     * Set up the database.
     *
     * @param  \Illuminate\Foundation\Application  $app
     */
    protected function setUpDatabase($app)
    {
        include_once __DIR__.'/../vendor/spatie/laravel-permission/database/migrations/create_permission_tables.php.stub';
        (new CreatePermissionTables())->up();
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

    protected function setUpSeed()
    {
        app()['cache']->forget('spatie.permission.cache');

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

        // Permissions
        $this->_permissions(Page::permissions());
        $this->_permissions(PageSoftDelete::permissions());
        $this->_permissions(Content::permissions());

        $roleAdmin = Role::create(['name' => 'admin']);
        $roleAdmin->givePermissionTo(Permission::all());
        $this->admin->assignRole($roleAdmin);
    }

    private function _permissions($permissions): array
    {

        foreach ($permissions as $p) {
            // Create Permissions
            Permission::create(['name' => $p, 'guard_name' => 'web']);
        }

        return $permissions;
    }

    protected function setUpRoutes()
    {

        // just test
        Route::get('test', 'PageStatusController@status')->name('frontend.page.show');
        Route::get('test2', 'PageStatusController@status')->name('frontend.page-sd.show');

        Route::group([
            'namespace' => 'HalcyonLaravel\Base\Tests\Http\Controllers\Backend\Core\Page',
            'prefix' => 'admin',
            'as' => 'admin.',
            // 'middleware' => 'admin'
        ], function () {
            Route::post('page/table', 'PagesTableController')->name('page.table');
            Route::get('page/status/{status}', 'PageStatusController@status')->name('page.status');
            Route::patch('page/status/{page}', 'PageStatusController@update')->name('page.status.update');
            Route::resource('page', 'PagesController');
        });
        // for observer
        Route::group([
            'namespace' => 'HalcyonLaravel\Base\Tests\Http\Controllers\Backend\Core\Page',
            'prefix' => 'admin',
            'as' => 'admin.',
            // 'middleware' => 'admin'
        ], function () {
            Route::resource('page-observer', 'PagesObserverController');
        });

        // Softdelete
        Route::group([
            'namespace' => 'HalcyonLaravel\Base\Tests\Http\Controllers\Backend',
            'prefix' => 'admin',
            'as' => 'admin.',
            // 'middleware' => 'admin'
        ], function () {
            Route::get('page-sd/deleted', 'Core\Page\PagesSoftDeleteController@deleted')->name('page-sd.deleted');
            Route::patch('page-sd/{page_sd}/deleted',
                'Core\Page\PagesSoftDeleteController@restore')->name('page-sd.restore');
            Route::delete('page-sd/{page_sd}/deleted',
                'Core\Page\PagesSoftDeleteController@purge')->name('page-sd.purge');
            Route::group([
                'namespace' => 'Core\Page',
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

    protected function bindingRepositories($app)
    {
        $app->bind(PageDeleteRepository::class, PageDeleteRepositoryEloquent::class);
        $app->bind(PageObserverRepository::class, PageObserverRepositoryEloquent::class);
        $app->bind(PageRepository::class, PageRepositoryEloquent::class);
    }

    public function tearDown(): void
    {
        parent::tearDown();
    }

    /**
     * Define environment setup.
     *
     * @param  \Illuminate\Foundation\Application  $app
     *
     * @return void
     */
    protected function getEnvironmentSetUp($app)
    {
        // Setup default database to use sqlite :memory:
        $app['config']->set('database.default', 'testbench');
        $app['config']->set('database.connections.testbench', [
            'driver' => 'sqlite',
            'database' => ':memory:',
            'prefix' => '',
        ]);
    }

    protected function getPackageAliases($app)
    {
        return [
            "DataTables" => "Yajra\\DataTables\\Facades\\DataTables",
            "MetaTag" => "Fomvasss\\LaravelMetaTags\\Facade",
        ];
    }

    protected function getPackageProviders($app)
    {
        return [
            "HalcyonLaravel\\Base\\Providers\\BaseServiceProvider",

            // --
            "Yajra\\DataTables\\DataTablesServiceProvider",
            "Spatie\\Permission\\PermissionServiceProvider",
            "Prettus\\Repository\\Providers\\RepositoryServiceProvider",
            "Fomvasss\\LaravelMetaTags\\ServiceProvider",
        ];
    }
}
