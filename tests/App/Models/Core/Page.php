<?php

namespace App\Models\Core;

use HalcyonLaravel\Base\Models\Model;
use Spatie\Sluggable\SlugOptions;
use HalcyonLaravel\Base\Models\Traits\ModelDefaultTraits;
use HalcyonLaravel\Base\Models\Contracts\ModelStatusContract;

class Page extends Model implements ModelStatusContract
{
    use ModelDefaultTraits;

    /**
     * Declared Fillables
     */
    protected $fillable = [
        'title', 'slug', 'description', 'status', 'template', 'type', 'url'
    ];


    public const MODULE_NAME         = 'page';
    public const VIEW_BACKEND_PATH    = 'backend.core.page';
    public const VIEW_FRONTEND_PATH   = 'frontend.core.page';
    public const ROUTE_ADMIN_PATH     = 'admin.page';
    public const ROUTE_FRONTEND_PATH  = 'frontend.page';

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
            'index' => 'page index',
            'show' => 'page show',
            'create' => 'page create',
            'edit' => 'page edit',
            'destroy' => 'page destroy',

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


    /**
     * Return the array of statuses.
     * ex. [ 0  => 'Disabled', 1 => 'Active' ], [ 'Disabled', 'Active'], [ 'disabled' => 'Disabled', 'active' => 'Active' ]
     *
     * @return array
     */
    public function statuses() : array
    {
        return [
            'enable' => 'Enable', 'disable' => 'Disable'
        ];
    }

    /**
     * Return the column for the status on this model.
     *
     * @return array
     */
    public function statusKeyName(): string
    {
        return 'status';
    }
}
