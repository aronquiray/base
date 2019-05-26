<?php

namespace HalcyonLaravel\Base\Tests\Units;

use HalcyonLaravel\Base\BaseableOptions;
use HalcyonLaravel\Base\Http\Controllers\Backend\CRUDController;
use HalcyonLaravel\Base\Http\Controllers\BaseController;
use HalcyonLaravel\Base\Repository\BaseRepositoryInterface;
use HalcyonLaravel\Base\Tests\Models\Content;
use HalcyonLaravel\Base\Tests\Repositories\ContentRepository;
use HalcyonLaravel\Base\Tests\TestCase;
use Illuminate\Database\Eloquent\Model as IlluminateModel;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class BaseControllerTest extends TestCase
{
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

    /**
     * @test
     */
    public function ajax_response()
    {
        $controller = new class extends CRUDController
        {
            /**
             * @return \HalcyonLaravel\Base\Repository\BaseRepositoryInterface
             */
            public function repository(): BaseRepositoryInterface
            {
                return app(ContentRepository::class);
            }

            /**
             * @param  \Illuminate\Http\Request  $request
             * @param  \Illuminate\Database\Eloquent\Model  $model
             *
             * @return array
             */
            public function generateStub(Request $request, IlluminateModel $model = null): array
            {
                return [];
            }

            /**
             * Validate input on store and update
             *
             * @param  \Illuminate\Http\Request  $request
             * @param  \Illuminate\Database\Eloquent\Model|null  $model
             *
             * @return \HalcyonLaravel\Base\BaseableOptions
             */
            public function crudRules(Request $request, IlluminateModel $model = null): BaseableOptions
            {
                return BaseableOptions::create();
            }
        };

        $this->assertInstanceOf(JsonResponse::class, $controller->response('update', true));
    }
}