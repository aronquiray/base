<?php

namespace HalcyonLaravel\Base\Tests\Units;

use  HalcyonLaravel\Base\Tests\TestCase;

class TestEvent extends TestCase
{
    public function setUp()
    {
        parent::setUp();
        file_put_contents(storage_path('logs/laravel.log'), "");
    }

    public function testLogStore()
    {
        event(new \HalcyonLaravel\Base\Events\BaseStoringEvent);
        $this->assertTrue(
            !empty(file_get_contents(storage_path('/logs/laravel.log')))
        );
    }
}
