<?php

namespace HalcyonLaravel\Base\Tests\Features;

use HalcyonLaravel\Base\Controllers\BaseController;
use HalcyonLaravel\Base\Repository\BaseRepositoryInterface;
use HalcyonLaravel\Base\Tests\Models\Content;
use HalcyonLaravel\Base\Tests\Repositories\ContentRepository;
use HalcyonLaravel\Base\Tests\TestCase;

class BaseControllerTest extends TestCase
{

    /**
     * @test
     */
    public function show_404()
    {
        $this->get(route('admin.page.show', 'im-not-really-exist'))
            ->assertStatus(404);
    }

    /**
     * @test
     */
    public function status_show_404()
    {
        $this->get(route('admin.page.status', 'im-not-really-exist'))
            ->assertStatus(404);
    }

    /**
     * @test
     */
    public function status_invalid_model()
    {
//        $this->expectExceptionMessage('Model must implemented in '.ModelStatusContract::class);
        $this->get(route('admin.content.status', 'im-not-really-exist'))
            ->assertStatus(500);
    }

    /**
     * @test
     */
    public function custom_where()
    {
        $controller = new class extends BaseController
        {
            /**
             * @return \HalcyonLaravel\Base\Repository\BaseRepositoryInterface
             */
            public function repository(): BaseRepositoryInterface
            {
                return app(ContentRepository::class);
            }
        };

        $data = [
            'name' => 'xxxxxxx',
            'content' => 'xxxxxxx',
            'description' => 'xxxxxxx',
            'image' => 'http://xxxxxx.com/me.png',
            'status' => 'active',
        ];

        $c = Content::create($data);

        $model = $controller->getModel($c->{$c->getRouteKeyName()}, false, ['id' => $c->id]);

        $this->assertEquals($c->id, $model->id);
    }
}