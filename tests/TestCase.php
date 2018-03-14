<?php

namespace HalcyonLaravel\Base\Tests;

use Illuminate\Database\Schema\Blueprint;
use App\Models\User;
use App\Models\Content;
use Orchestra\Testbench\TestCase as Orchestra;

class TestCase extends Orchestra
{
    protected $user;
    protected $admin;
    protected $content;
    
    public function setUp()
    {
        parent::setUp();
        $this->setUpDatabase($this->app);
        $this->setUpSeed();
    }


    public function tearDown()
    {
        parent::tearDown();
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
    }

    /**
     * Set up the database.
     *
     * @param \Illuminate\Foundation\Application $app
     */
    protected function setUpDatabase($app)
    {
        // test Migrations
        $app['db']->connection()->getSchemaBuilder()->create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('first_name');
            $table->string('last_name');
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->timestamps();
        });



        // Main Migration
        $app['db']->connection()->getSchemaBuilder()->create('contents', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->unique();
            $table->text('content');
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
        ];
    }

    protected function getPackageProviders($app)
    {
        return [
                "HalcyonLaravel\\Base\\Providers\\BaseServiceProvider",
        ];
    }
}
