<?php

namespace HalcyonLaravel\Base\Tests\Features;

use HalcyonLaravel\Base\Tests\TestCase;
use App\Models\Core\Page;

class TestDataTable extends TestCase
{
    public function setUp()
    {
        parent::setUp();
        $this->actingAs($this->admin);
        Page::truncate();
    }
    
    public function testNoData()
    {
        $response = $this->json('POST', route('admin.page.table'), []);

        $response
                ->assertStatus(200)
                ->assertJson([
                "draw"=> 0,
                        "recordsTotal"=> 0,
                        "recordsFiltered"=> 0,
                        "data"=> []
                ]);
    }

    public function testWithDataOneRow()
    {
        $page = Page::create([
            'title' => 'Unit Test Title',
            'status' => 'enable',
            'description' => 'Dessdription blah blah',
        ]);

        $expectedJson = [
            "draw"=> 0,
            "recordsTotal"=> 1,
            "recordsFiltered"=> 1,
            "data"=>[
                [
                    "title"=> $page->title,
                    "slug"=> $page->slug,
                    "description"=> $page->description,
                    "status"=> [
                        "type"=> "success",
                        "label"=> "Enable",
                        "value"=> "enable",
                        "link"=> "http://localhost/admin/page/{$page->slug}/status",
                        "can"=> false
                    ],
                    "template"=> null,
                    "type"=> null,
                    "url"=> null,
                    "updated_at"=> "20 Mar, 2018 06:03 AM",
                    "actions"=> [
                        "show"=> [
                            "type"=> "show",
                            "url"=> "http://localhost/admin/page/{$page->slug}"
                        ],
                        "edit"=> [
                            "type"=> "edit",
                            "url"=> "http://localhost/admin/page/{$page->slug}/edit"
                        ],
                        "destroy"=> [
                            "type"=> "destroy",
                            "url"=> "http://localhost/admin/page/{$page->slug}",
                            "group"=> "more",
                            "redirect"=> "http://localhost/admin/page"
                        ]
                    ]
                ],
            ]
            
        ];

        
        $response = $this->json('POST', route('admin.page.table'), []);

        $response
                ->assertStatus(200)
                ->assertJson($expectedJson);
    }
}
