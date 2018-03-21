<?php

namespace HalcyonLaravel\Base\Tests\Units;

use HalcyonLaravel\Base\Tests\TestCase;
use HalcyonLaravel\Base\Repository\BaseRepository;
use App\Models\Core\Page;
use Route;

class TestExceptions extends TestCase
{
    public function testMethodNotFound()
    {
        Route::get('test', 'App\Http\Controllers\Backend\Core\Page\PagesController@testForMethodNotFound')
            ->name('test-me');

        $response =  $this->json('GET', route('test-me'), []);

        //   dd($response);
        $response
            ->assertStatus(500)
            ->assertExactJson([
                    'message'=>'Server Error',
                    // 'exception:'=>[
                    //     'message'=>'not fooo',
                    // ]
            ]);
    }
}
