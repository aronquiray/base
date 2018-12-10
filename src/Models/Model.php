<?php

namespace HalcyonLaravel\Base\Models;

use HalcyonLaravel\Base\Models\Traits\ModelTraits;
use Illuminate\Database\Eloquent\Model as BaseModel;

/**
 * Class Model.
 */
abstract class Model extends BaseModel implements Contracts\BaseModel
{
    use ModelTraits;

    /**
     * @param $query
     * @param array $columns
     * @return mixed
     */
    public function scopeExclude($query, array $columns = [])
    {
        return $query->select(array_diff($this->getFillable(), $columns));
    }

    /**
     * @param $field
     * @return mixed
     */
    public function getTrans($field)
    {
        $locale = config('app.locale');

        if (session()->has('locale') && in_array(session()->get('locale'), array_keys(config('locale.languages')))) {
            $locale = session()->get('locale');
        }

        return $this->getTranslation($field, $locale);
    }
}
