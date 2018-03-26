<?php

namespace HalcyonLaravel\Base\Tests\Features;

use HalcyonLaravel\Base\Tests\TestCase;
use App\Models\Core\Page;
use App\Models\Core\PageSoftDelete;
use Faker\Factory as Faker;

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
        $faker = Faker::create();
        $now = now()->format('Y-m-d H:i:s');
        foreach (range(1, 20) as $index) {
            Page::create([
                    'title' => $faker->sentence(),
                    'status' => 'enable',
                    'description' => $faker->sentence(50),
                    'updated_at' => $now,
                ]);
        }

        $pages = [];

        foreach (Page::all() as $page) {
            $pages[] = [
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
                "updated_at"=> $page->updated_at->format('d M, Y h:m A'),
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
            ];
        }

        $expectedJson = [
            "draw"=> 0,
            "recordsTotal"=> count($pages),
            "recordsFiltered"=>  count($pages),
            "data"=>$pages
        ];

        
        $response = $this->json('POST', route('admin.page.table'), []);

        $response
                ->assertStatus(200)
                ->assertJson($expectedJson);
    }
    
    public function testWithSofdeletedDataOneRowNotDeleted()
    {
        PageSoftDelete::truncate();
        $faker = Faker::create();
        $now = now()->format('Y-m-d H:i:s');
        foreach (range(1, 20) as $index) {
            $p = PageSoftDelete::create([
                    'title' => $faker->sentence(),
                    'status' => 'enable',
                    'description' => $faker->sentence(50),
                    'updated_at' => $now,
                ]);
        }

        $pages = [];

        foreach (PageSoftDelete::all() as $page) {
            $pages[] = [
                "title"=> $page->title,
                "slug"=> $page->slug,
                "description"=> $page->description,
                "status"=> [
                    "type"=> "success",
                    "label"=> "Enable",
                    "value"=> "enable",
                    "link"=> "http://localhost/admin/page-sd/{$page->slug}/status",
                    "can"=> false
                ],
                "template"=> null,
                "type"=> null,
                "url"=> null,
                "updated_at"=> $page->updated_at->format('d M, Y h:m A'),
                "actions"=> [
                    "show"=> [
                        "type"=> "show",
                        "url"=> "http://localhost/admin/page-sd/{$page->slug}"
                    ],
                    "edit"=> [
                        "type"=> "edit",
                        "url"=> "http://localhost/admin/page-sd/{$page->slug}/edit"
                    ],
                    "destroy"=> [
                        "type"=> "destroy",
                        "url"=> "http://localhost/admin/page-sd/{$page->slug}",
                        "group"=> "more",
                        "redirect"=> "http://localhost/admin/page-sd"
                    ]
                ]
            ];
        }

        $expectedJson = [
            "draw"=> 0,
            "recordsTotal"=> count($pages),
            "recordsFiltered"=> count($pages),
            "data"=>$pages
        ];

        
        $response = $this->json('POST', route('admin.page-sd.table'), []);

        $response
                ->assertStatus(200)
                ->assertJson($expectedJson);
    }

    public function testWithSofdeletedDataOneRowYESDeleted()
    {
        PageSoftDelete::truncate();
        $faker = Faker::create();
        $now = now()->format('Y-m-d H:i:s');
        foreach (range(1, 1) as $index) {
            $p = PageSoftDelete::create([
                    'title' => $faker->sentence(),
                    'status' => 'enable',
                    'description' => $faker->sentence(50),
                    'updated_at' => $now,
                ]);
            $p->deleted_at =$now;
            $p->save();
        }

        $pages = [];

        foreach (PageSoftDelete::withTrashed()->get() as $page) {
            $pages[] = [
                "title"=> $page->title,
                "slug"=> $page->slug,
                "description"=> $page->description,
                "status"=> [
                    "type"=> "success",
                    "label"=> "Enable",
                    "value"=> "enable",
                    "link"=> "http://localhost/admin/page-sd/{$page->slug}/status",
                    "can"=> false
                ],
                "template"=> null,
                "type"=> null,
                "url"=> null,
                "updated_at"=> $page->updated_at->format('d M, Y h:m A'),
                "actions"=> [
                    "show"=> [
                        "type"=> "show",
                        "url"=> "http://localhost/admin/page-sd/{$page->slug}"
                    ],
                    "edit"=> [
                        "type"=> "edit",
                        "url"=> "http://localhost/admin/page-sd/{$page->slug}/edit"
                    ],
                    "destroy"=> [
                        "type"=> "destroy",
                        "url"=> "http://localhost/admin/page-sd/{$page->slug}",
                        "group"=> "more",
                        "redirect"=> "http://localhost/admin/page-sd"
                    ]
                ]
            ];
        }

        $expectedJson = [
            "draw"=> 0,
            "recordsTotal"=> count($pages),
            "recordsFiltered"=> count($pages),
            "data"=>$pages
        ];

        
        $response = $this->json('POST', route('admin.page-sd.table'), ['trash' => true]);

        $response
                ->assertStatus(200)
                ->assertJson($expectedJson);
    }
}
