<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use HalcyonLaravel\Base\Traits\Baseable;
use HalcyonLaravel\Base\Contracts\BaseableInterface;

class Content extends Model implements BaseableInterface
{
    use Baseable;

    protected $fillable = [
        'name',
        'content',
    ];

    /**
     * Return the baseable name for this model.
     *
     * @return array
     */
    public function baseable() :array
    {
        return [
            'source' => 'name'
        ];
    }

    /**
     * Return the sluggable configuration array for this model.
     *
     * @return array
     */
    public function sluggable()
    {
        return [
            'slug' => [
                'source' => 'name'
            ]
        ];
    }
}
