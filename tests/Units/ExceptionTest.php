<?php

namespace HalcyonLaravel\Base\Tests\Units;

use HalcyonLaravel\Base\Tests\TestCase;
use Illuminate\Support\Facades\Route;

class ExceptionTest extends TestCase
{
    /**
     * @test
     */
    public function method_not_found()
    {
        Route::get('test',
            'HalcyonLaravel\Base\Tests\Http\Controllers\Backend\Core\Page\PagesController@testForMethodNotFound')->name('test-me');

        $response = $this->json('GET', route('test-me'), []);

        //   dd($response);
        $response->assertStatus(500)->assertExactJson([
            'message' => 'Server Error',
            // 'exception:'=>[
            //     'message'=>'not fooo',
            // ]
        ]);
    }
}
