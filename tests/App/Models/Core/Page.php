<?php

namespace App\Models\Core;

use HalcyonLaravel\Base\Models\Model;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;
use HalcyonLaravel\Base\Models\Traits\ModelDefaultTraits as DefaultTrait;

class Page extends Model
{
    use DefaultTrait,HasSlug;
    /**
     * Declared Fillables
     */
    protected $fillable = [
        'title', 'slug', 'content', 'description', 'status', 'template', 'type', 'url'
    ];


    public $module_name          = 'page';
    public $view_backend_path    = 'backend.core.page';
    public $view_frontend_path   = 'frontend.core.page';
    public $route_admin_path     = 'admin.page';
    public $route_frontend_path  = 'frontend.page';

    /**
     * Get the options for generating the slug.
     */
    public function getSlugOptions() : SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('title')
            ->saveSlugsTo('slug');
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
}
