<?php

namespace HalcyonLaravel\Base\Tests\Features;

use Faker\Factory as Faker;
use HalcyonLaravel\Base\Tests\Models\Core\Page;
use HalcyonLaravel\Base\Tests\Models\Core\PageSoftDelete;
use HalcyonLaravel\Base\Tests\TestCase;

class DataTableTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();
        $this->actingAs($this->admin);
        Page::query()->truncate();
    }

    /**
     * @test
     */
    public function no_data()
    {
        $response = $this->post(route('admin.page.table'), []);

        $response->assertStatus(200)->assertJson([
            "draw" => 0,
            "recordsTotal" => 0,
            "recordsFiltered" => 0,
            "data" => [],
        ]);
    }

    /**
     * @test
     */
    public function with_data_one_row_()
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
                "title" => $page->title,
                "description" => $page->description,
                "status" => [
                    "type" => "success",
                    "label" => "Enable",
                    "value" => "enable",
                    "link" => "http://localhost/admin/page/status/{$page->id}",
                    "can" => false,
                ],
                "template" => null,
                "type" => null,
                "url" => null,
                "updated_at" => $page->updated_at->format('d M, Y h:m A'),
                "actions" => [
                    "show" => [
                        "type" => "show",
                        "url" => "http://localhost/admin/page/{$page->id}",
                    ],
                ],
            ];
        }

        $expectedJson = [
            "draw" => 0,
            "recordsTotal" => count($pages),
            "recordsFiltered" => count($pages),
            "data" => $pages,
        ];

        $response = $this->json('POST', route('admin.page.table'), []);

        $response->assertStatus(200)->assertJson($expectedJson);
    }

    /**
     * @test
     */
    public function with_soft_deleted_data_one_row_not_deleted()
    {
        PageSoftDelete::query()->truncate();
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
                "title" => $page->title,
                "description" => $page->description,
                "status" => [
                    "type" => "success",
                    "label" => "Enable",
                    "value" => "enable",
                    "link" => "http://localhost/admin/page-sd/status/{$page->id}",
                    "can" => false,
                ],
                "template" => null,
                "type" => null,
                "url" => null,
                "updated_at" => $page->updated_at->format('d M, Y h:m A'),
                "actions" => [
                    "show" => [
                        "type" => "show",
                        "url" => "http://localhost/admin/page-sd/{$page->id}",
                    ],
                    "edit" => [
                        "type" => "edit",
                        "url" => "http://localhost/admin/page-sd/{$page->id}/edit",
                    ],
                    "destroy" => [
                        "type" => "destroy",
                        "url" => "http://localhost/admin/page-sd/{$page->id}",
                        "group" => "more",
                        "redirect" => "http://localhost/admin/page-sd",
                    ],
                ],
            ];
        }

        $expectedJson = [
            "draw" => 0,
            "recordsTotal" => count($pages),
            "recordsFiltered" => count($pages),
            "data" => $pages,
        ];

        $response = $this->json('POST', route('admin.page-sd.table'), []);

        $response->assertStatus(200)->assertJson($expectedJson);
    }

    /**
     * @test
     */
    public function with_soft_deleted_data_one_row_yes_deleted()
    {
        PageSoftDelete::query()->truncate();
        $faker = Faker::create();
        $now = now()->format('Y-m-d H:i:s');
        foreach (range(1, 1) as $index) {
            $p = PageSoftDelete::create([
                'title' => $faker->sentence(),
                'status' => 'enable',
                'description' => $faker->sentence(50),
                'updated_at' => $now,
            ]);
            $p->deleted_at = $now;
            $p->save();
        }

        $pages = [];

        foreach (PageSoftDelete::withTrashed()->get() as $page) {
            $pages[] = [
                "title" => $page->title,
                "description" => $page->description,
                "status" => [
                    "type" => "success",
                    "label" => "Enable",
                    "value" => "enable",
                    "link" => "http://localhost/admin/page-sd/status/{$page->id}",
                    "can" => false,
                ],
                "template" => null,
                "type" => null,
                "url" => null,
                "updated_at" => $page->updated_at->format('d M, Y h:m A'),
                "actions" => [
                    "show" => [
                        "type" => "show",
                        "url" => "http://localhost/admin/page-sd/{$page->id}",
                    ],
                    "edit" => [
                        "type" => "edit",
                        "url" => "http://localhost/admin/page-sd/{$page->id}/edit",
                    ],
                    "destroy" => [
                        "type" => "destroy",
                        "url" => "http://localhost/admin/page-sd/{$page->id}",
                        "group" => "more",
                        "redirect" => "http://localhost/admin/page-sd",
                    ],
                ],
            ];
        }

        $expectedJson = [
            "draw" => 0,
            "recordsTotal" => count($pages),
            "recordsFiltered" => count($pages),
            "data" => $pages,
        ];

        $response = $this->json('POST', route('admin.page-sd.table'), ['trash' => true]);

        $response->assertStatus(200)->assertJson($expectedJson);
    }
}
