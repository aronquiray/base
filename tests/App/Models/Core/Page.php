<?php

namespace HalcyonLaravel\Base\Tests\Models\Core;

use HalcyonLaravel\Base\Models\Contracts\ModelStatusContract;
use HalcyonLaravel\Base\Models\Model;
use HalcyonLaravel\Base\Models\Traits\ModelDefaultTraits;

class Page extends Model implements ModelStatusContract
{
    use ModelDefaultTraits;

    public const MODULE_NAME = 'page';

    public const VIEW_BACKEND_PATH = 'backend.core.page';

    public const VIEW_FRONTEND_PATH = 'frontend.core.page';

    public const ROUTE_ADMIN_PATH = 'admin.page';

    public const ROUTE_FRONTEND_PATH = 'frontend.page';

    /**
     * Declared Fillables
     */
    protected $fillable = [
        'id',
        'title',
        'description',
        'status',
        'template',
        'type',
        'url',
    ];

    /**
     * Return the permissions related to this model.
     *
     * @return array
     */
    public static function permissions(): array
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
     * Return the baseable source for this model.
     *
     * @return array
     */
    public function baseable(): array
    {
        return [
            'source' => 'title',
        ];
    }

    /**
     * Return the array of statuses.
     * ex. [ 0  => 'Disabled', 1 => 'Active' ], [ 'Disabled', 'Active'], [ 'disabled' => 'Disabled', 'active' => 'Active' ]
     *
     * @return array
     */
    public function statuses(): array
    {
        return [
            'enable' => 'Enable',
            'disable' => 'Disable',
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
