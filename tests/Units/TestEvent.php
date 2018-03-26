<?php

namespace HalcyonLaravel\Base\Tests\Units;

use  HalcyonLaravel\Base\Tests\TestCase;


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

class TestEvent extends TestCase
{
    public function setUp()
    {
        parent::setUp();
        file_put_contents(storage_path('logs/laravel.log'), "");
    }

    public function testLogStoring()
    {
        event(new BaseStoringEvent);
        $this->assertTrue(
            !empty(file_get_contents(storage_path('/logs/laravel.log')))
        );
    }
    public function testLogStored()
    {
        event(new BaseStoredEvent);
        $this->assertTrue(
            !empty(file_get_contents(storage_path('/logs/laravel.log')))
        );
    }

    public function testLogUpdating()
    {
        event(new BaseUpdatingEvent);
        $this->assertTrue(
            !empty(file_get_contents(storage_path('/logs/laravel.log')))
        );
    }
    public function testLogUpdated()
    {
        event(new BaseUpdatedEvent);
        $this->assertTrue(
            !empty(file_get_contents(storage_path('/logs/laravel.log')))
        );
    }

    public function testLogDeleting()
    {
        event(new BaseDeletingEvent);
        $this->assertTrue(
            !empty(file_get_contents(storage_path('/logs/laravel.log')))
        );
    }
    public function testLogDeleted()
    {
        event(new BaseDeletedEvent);
        $this->assertTrue(
            !empty(file_get_contents(storage_path('/logs/laravel.log')))
        );
    }

    public function testLogRestoring()
    {
        event(new BaseRestoringEvent);
        $this->assertTrue(
            !empty(file_get_contents(storage_path('/logs/laravel.log')))
        );
    }
    public function testLogRestored()
    {
        event(new BaseRestoredEvent);
        $this->assertTrue(
            !empty(file_get_contents(storage_path('/logs/laravel.log')))
        );
    }

    public function testLogPurging()
    {
        event(new BasePurgingEvent);
        $this->assertTrue(
            !empty(file_get_contents(storage_path('/logs/laravel.log')))
        );
    }
    public function testLogPurged()
    {
        event(new BasePurgedEvent);
        $this->assertTrue(
            !empty(file_get_contents(storage_path('/logs/laravel.log')))
        );
    }
}
