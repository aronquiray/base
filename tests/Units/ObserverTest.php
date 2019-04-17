<?php

namespace HalcyonLaravel\Base\Tests\Units;

use HalcyonLaravel\Base\Tests\App\Repositories\Observer;
use HalcyonLaravel\Base\Tests\Models\Core\Page;
use HalcyonLaravel\Base\Tests\Models\Core\PageSoftDelete;
use HalcyonLaravel\Base\Tests\TestCase;
use InvalidArgumentException;

class ObserverTest extends TestCase
{
    /**
     * @test
     */
    public function check_before_delete_invalid_argument()
    {
        $this->expectException(InvalidArgumentException::class);
        $observer = new class extends Observer
        {
            public function test()
            {
                parent::checkBeforeDelete('im-invalid', Page::first(), function () {

                });
            }
        };
        $observer->test();
    }

    /**
     * @test
     */
    public function destroy_not_soft_delete_execute()
    {
        $page = Page::create([
            'title' => 'destroy_not_soft_delete',
            'status' => 'enable',
        ]);
        $observer = new class extends Observer
        {
            public function test()
            {
                $model = Page::where('title', 'destroy_not_soft_delete')->first();
                parent::checkBeforeDelete('destroy', $model, function () use ($model) {
                    $model->update([
                        'title' => 'im update destroy_not_soft_delete',
                    ]);
                });
            }
        };
        $observer->test();

        $this->assertEquals('im update destroy_not_soft_delete', $page->fresh()->title);
    }

    /**
     * @test
     */
    public function purge_not_soft_delete_not_execute()
    {
        $page = Page::create([
            'title' => 'purge_not_soft_delete_not_execute',
            'status' => 'enable',
        ]);
        $observer = new class extends Observer
        {
            public function test()
            {
                $model = Page::where('title', 'purge_not_soft_delete_not_execute')->first();
                parent::checkBeforeDelete('purge', $model, function () use ($model) {
                    $model->update([
                        'title' => 'im update purge_not_soft_delete_not_execute',
                    ]);
                });
            }
        };
        $observer->test();

        $this->assertEquals('purge_not_soft_delete_not_execute', $page->fresh()->title);
    }

    /**
     * @test
     */
    public function destroy_soft_delete_not_execute()
    {
        $page = PageSoftDelete::create([
            'title' => 'destroy_soft_delete_not_execute',
            'status' => 'enable',
        ]);
        $observer = new class extends Observer
        {
            public function test()
            {
                $model = PageSoftDelete::where('title', 'destroy_soft_delete_not_execute')->first();
                parent::checkBeforeDelete('destroy', $model, function () use ($model) {
                    $model->update([
                        'title' => 'im update destroy_soft_delete_not_execute',
                    ]);
                });
            }
        };
        $observer->test();

        $this->assertEquals('destroy_soft_delete_not_execute', $page->fresh()->title);

    }

    /**
     * @test
     */
    public function purge_soft_delete_execute()
    {
        $page = PageSoftDelete::create([
            'title' => 'purge_soft_delete_execute',
            'status' => 'enable',
        ]);
        $observer = new class extends Observer
        {
            public function test()
            {
                $model = PageSoftDelete::where('title', 'purge_soft_delete_execute')->first();
                parent::checkBeforeDelete('purge', $model, function () use ($model) {
                    $model->update([
                        'title' => 'im update purge_soft_delete_execute',
                    ]);
                });
            }
        };
        $observer->test();

        $this->assertEquals('im update purge_soft_delete_execute', $page->fresh()->title);
    }
}