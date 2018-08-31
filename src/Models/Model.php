<?php

namespace HalcyonLaravel\Base\Models;

use Illuminate\Database\Eloquent\Model as BaseModel;

use HalcyonLaravel\Base\Models\Traits\ModelTraits;

/**
 * Class Model.
 */
abstract class Model extends BaseModel
{
    use ModelTraits;
    
    
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

    public function scopeExclude($query, array $columns = [])
    {
        return $query->select(array_diff($this->getFillable(), $columns));
    }

    public function getTrans($field)
    {
        $locale = config('app.locale');

        if (session()->has('locale') && in_array(session()->get('locale'), array_keys(config('locale.languages')))) {
            $locale = session()->get('locale');
        }

        return $this->getTranslation($field, $locale);
    }
}
