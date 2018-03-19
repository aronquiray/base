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

    public function testLogStoring()
    {
        event(new \HalcyonLaravel\Base\Events\BaseStoringEvent);
        $this->assertTrue(
            !empty(file_get_contents(storage_path('/logs/laravel.log')))
        );
    }
    public function testLogStored()
    {
        event(new \HalcyonLaravel\Base\Events\BaseStoredEvent);
        $this->assertTrue(
            !empty(file_get_contents(storage_path('/logs/laravel.log')))
        );
    }

    public function testLogUpdating()
    {
        event(new \HalcyonLaravel\Base\Events\BaseUpdatingEvent);
        $this->assertTrue(
            !empty(file_get_contents(storage_path('/logs/laravel.log')))
        );
    }
    public function testLogUpdated()
    {
        event(new \HalcyonLaravel\Base\Events\BaseUpdatedEvent);
        $this->assertTrue(
            !empty(file_get_contents(storage_path('/logs/laravel.log')))
        );
    }

    public function testLogDeleting()
    {
        event(new \HalcyonLaravel\Base\Events\BaseDeletingEvent);
        $this->assertTrue(
            !empty(file_get_contents(storage_path('/logs/laravel.log')))
        );
    }
    public function testLogDeleted()
    {
        event(new \HalcyonLaravel\Base\Events\BaseDeletedEvent);
        $this->assertTrue(
            !empty(file_get_contents(storage_path('/logs/laravel.log')))
        );
    }

    public function testLogRestoring()
    {
        event(new \HalcyonLaravel\Base\Events\BaseRestoringEvent);
        $this->assertTrue(
            !empty(file_get_contents(storage_path('/logs/laravel.log')))
        );
    }
    public function testLogRestored()
    {
        event(new \HalcyonLaravel\Base\Events\BaseRestoredEvent);
        $this->assertTrue(
            !empty(file_get_contents(storage_path('/logs/laravel.log')))
        );
    }

    public function testLogPurging()
    {
        event(new \HalcyonLaravel\Base\Events\BasePurgingEvent);
        $this->assertTrue(
            !empty(file_get_contents(storage_path('/logs/laravel.log')))
        );
    }
    public function testLogPurged()
    {
        event(new \HalcyonLaravel\Base\Events\BasePurgedEvent);
        $this->assertTrue(
            !empty(file_get_contents(storage_path('/logs/laravel.log')))
        );
    }
}
