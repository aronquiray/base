<?php

namespace App\Models\Core;

use HalcyonLaravel\Base\Models\Model;
use Spatie\Sluggable\SlugOptions;
use Spatie\Sluggable\HasSlug;
use Illuminate\Database\Eloquent\SoftDeletes;
use HalcyonLaravel\Base\Models\Traits\ModelDefaultTraits;

class PageSoftDelete extends Model
{
    use HasSlug;
    use SoftDeletes,ModelDefaultTraits;
    protected $table = 'pages_sd';
    /**
     * Declared Fillables
     */
    protected $fillable = [
        'title', 'slug', 'description', 'status', 'template', 'type', 'url'
    ];


    public const MODULE_NAME         = 'page-sd';
    public const VIEW_BACKEND_PATH    = 'backend.core.page-sd';
    public const VIEW_FRONTEND_PATH   = 'frontend.core.page-sd';
    public const ROUTE_ADMIN_PATH     = 'admin.page-sd';
    public const ROUTE_FRONTEND_PATH  =  'frontend.page-sd';

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
     * Return the permissions related to this model.
     *
     * @return array
     */
    public static function permissions() : array
    {
        return [
            'index' => 'page softdelete index',
            'show' => 'page softdelete show',
            'create' => 'page softdelete create',
            'edit' => 'page softdelete edit',
            'destroy' => 'page softdelete destroy',
            'restore' => 'page softdelete restore',
            'purge' => 'page softdelete purge',

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


    public function additionalLinks()
    {
        return [];
    }
}
