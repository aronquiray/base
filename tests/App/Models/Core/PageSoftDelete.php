<?php

namespace App\Models\Core;

use HalcyonLaravel\Base\Models\Model;
use Spatie\Sluggable\SlugOptions;
use Illuminate\Database\Eloquent\SoftDeletes;

class PageSoftDelete extends Model
{
    use SoftDeletes;
    protected $table = 'pages_sd';
    /**
     * Declared Fillables
     */
    protected $fillable = [
        'title', 'slug', 'content', 'description', 'status', 'template', 'type', 'url'
    ];


    public $module_name          = 'page';
    public $view_backend_path    = 'backend.core.page';
    public $view_frontend_path   = 'frontend.core.page';
    public $route_admin_path     = 'admin.page-sd';
    public $route_frontend_path  = 'frontend.page';

    /**
     * Return the sluggable configuration array for this model.
     *
     * @return array
     */
    public function sluggable()
    {
        return [
            'slug' => [
                'source' => 'title'
            ]
        ];
    }

    public function getRouteKeyName()
    {
        return 'slug';
    }

    /**
     * Return the baseable source for this model.
     *
     * @return array
     */
    public function baseable() : array
    {
        return [
            'source' => 'title'
        ];
    }

    /**
     * Return the baseable source for this model.
     *
     * @return array
     */
    public function links() : array
    {
        return [

        ];
    }

    /**
     * Get the options for generating the slug.
     */
    public function getSlugOptions() : SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('title')
            ->saveSlugsTo('slug');
    }
}
