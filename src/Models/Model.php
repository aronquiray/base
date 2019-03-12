<?php

namespace HalcyonLaravel\Base\Models;

use HalcyonLaravel\Base\Enforcer;
use HalcyonLaravel\Base\Models\Contracts\BaseModelInterface;
use HalcyonLaravel\Base\Models\Contracts\BaseModelPermissionInterface;
use HalcyonLaravel\Base\Models\Traits\ModelTraits;
use Illuminate\Database\Eloquent\Model as BaseModel;

/**
 * Class Model
 *
 * @package HalcyonLaravel\Base\Models
 */
abstract class Model extends BaseModel implements BaseModelInterface, BaseModelPermissionInterface
{
    use ModelTraits;

    const MODULE_NAME = 'abstract';
    const VIEW_BACKEND_PATH = 'abstract';
    const VIEW_FRONTEND_PATH = 'abstract';
    const ROUTE_ADMIN_PATH = 'abstract';
    const ROUTE_FRONTEND_PATH = 'abstract';

    /**
     * Model constructor.
     *
     * @param array $attributes
     *
     * @throws \ReflectionException
     */
    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        Enforcer::__add(__CLASS__, get_called_class());
    }

    /**
     * @param       $query
     * @param array $columns
     *
     * @return mixed
     */
    public function scopeExclude($query, array $columns = [])
    {
        return $query->select(array_diff($this->getFillable(), $columns));
    }

    /**
     * @param $field
     *
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
