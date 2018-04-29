<?php

namespace HalcyonLaravel\Base\Models;

use Illuminate\Database\Eloquent\Model as BaseModel;

use Spatie\Sluggable\HasSlug;
use HalcyonLaravel\Base\Models\Traits\ModelTraits;

/**
 * Class Model.
 */
abstract class Model extends BaseModel
{
    use HasSlug, ModelTraits;
    
    
    /**
     * Return the links related to this model.
     *
     * @return array
     */
    abstract public function links(): array;

    /**
     * Return the baseable configuration array for this model.
     *
     * @return array
     */
    abstract public function baseable(): array;

    /**
     * Return all the permissions for this model.
     *
     * @return array
     */
    abstract public static function permissions(): array;
}
