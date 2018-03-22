<?php

namespace App\Models\Core;

use HalcyonLaravel\Base\Models\Model;
use Spatie\Sluggable\SlugOptions;
use Illuminate\Database\Eloquent\SoftDeletes;
use HalcyonLaravel\Base\Models\Traits\ModelDefaultTraits;

class PageSoftDelete extends Model
{
    use SoftDeletes,ModelDefaultTraits;
    protected $table = 'pages_sd';
    /**
     * Declared Fillables
     */
    protected $fillable = [
        'title', 'slug', 'description', 'status', 'template', 'type', 'url'
    ];


    public const moduleName         = 'page-sd';
    public const viewBackendPath    = 'backend.core.page-sd';
    public const viewFrontendPath   = 'frontend.core.page-sd';
    public const routeAdminPath     = 'admin.page-sd';
    public const routeFrontendPath  =  'frontend.page-sd';

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
     * Get the options for generating the slug.
     */
    public function getSlugOptions() : SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('title')
            ->saveSlugsTo('slug');
    }
}
