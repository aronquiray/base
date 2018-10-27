<?php

namespace HalcyonLaravel\Base\Models;

use HalcyonLaravel\Base\Models\Traits\ModelTraits;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model as BaseModel;
use Illuminate\Support\Facades\Schema;

/**
 * Class Model.
 */
abstract class Model extends BaseModel
{
    use ModelTraits;

    /**
     * Return all the permissions for this model.
     *
     * @return array
     */
    abstract public static function permissions(): array;

    public static function boot()
    {
        parent::boot();
        static::addGlobalScope('latest', function (Builder $builder) {
            $tableName = (new static)->getTable();
            if (Schema::hasColumn($tableName, 'updated_at')) {
                $builder->latest('updated_at');
            } elseif (Schema::hasColumn($tableName, 'created_at')) {
                $builder->latest('created_at');
            }
        });
    }

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
