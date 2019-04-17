<?php

namespace HalcyonLaravel\Base\Models;

use Exception;
use HalcyonLaravel\Base\Enforcer;
use HalcyonLaravel\Base\Models\Contracts\BaseModelInterface;
use HalcyonLaravel\Base\Models\Contracts\BaseModelPermissionInterface;
use HalcyonLaravel\Base\Models\Traits\ModelTraits;
use Illuminate\Database\Eloquent\Model as BaseModel;
use Spatie\Translatable\HasTranslations;

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
     * @param  array  $attributes
     *
     * @throws \ReflectionException
     */
    public function __construct(array $attributes = [])
    {
        Enforcer::__add(__CLASS__, get_called_class());
        parent::__construct($attributes);
    }

    /**
     * @param       $query
     * @param  array  $columns
     *
     * @return mixed
     * @codeCoverageIgnore
     */
    public function scopeExclude($query, array $columns = [])
    {
        return $query->select(array_diff($this->getFillable(), $columns));
    }

    /**
     * @param  string  $key
     * @param  string  $locale
     * @param  bool  $useFallbackLocale
     *
     * @return mixed
     * @throws \Exception
     * @codeCoverageIgnore
     */
    public function getTrans(string $key, string $locale = null, bool $useFallbackLocale = true)
    {
        if (!is_class_uses_deep($this, HasTranslations::class)) {
            throw new Exception('Model must uses '.HasTranslations::class);
        }

        if (!is_null($locale)
            && session()->has('locale')
            && in_array(
                session()->get('locale'),
                array_keys(config('locale.languages'))
            )
        ) {
            $locale = session()->get('locale');
        }

        if (is_null($locale)) {
            $locale = config('app.locale');
        }

        return parent::getTranslation($key, $locale, $useFallbackLocale);
    }
}
