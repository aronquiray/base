<?php

namespace App\Models;

use HalcyonLaravel\Base\Models\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

class Content extends Model
{
    use HasSlug;
    
    protected $fillable = [
        'name',
        'content',
        'slug',
    ];

    /**
     * Return the baseable name for this model.
     *
     * @return array
     */
    public function baseable() :array
    {
        return [
            'history_name' => 'name'
        ];
    }

    /**
     * Return the links related to this model.
     *
     * @return array
     */
    public function links(): array
    {
        return [];
    }


    /**
     * Return the permissions related to this model.
     *
     * @return array
     */
    public static function permissions() : array
    {
        return [
            'index' => 'content index',
            'show' => 'content show',
            'create' => 'content create',
            'edit' => 'content edit',
            'destroy' => 'content destroy',

        ];
    }
    
    
    /**
     * Get the options for generating the slug.
     */
    public function getSlugOptions() : SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom(['first_name', 'last_name'])
            ->saveSlugsTo('slug');
    }
}
