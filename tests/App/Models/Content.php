<?php

namespace HalcyonLaravel\Base\Tests\Models;

use HalcyonLaravel\Base\Models\Model;

class Content extends Model
{
    public const MODULE_NAME = 'content';
    public const VIEW_BACKEND_PATH = 'backend.content';
    public const VIEW_FRONTEND_PATH = 'frontend.content';
    public const ROUTE_ADMIN_PATH = 'admin.contents';
    public const ROUTE_FRONTEND_PATH = 'frontend.contents';
    protected $fillable = [
        'id',
        'name',
        'content',
    ];

    /**
     * Return the permissions related to this model.
     *
     * @return array
     */
    public static function permissions(): array
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
     * Return the baseable name for this model.
     *
     * @return array
     */
    public function baseable(): array
    {
        return [
            'history_name' => 'name',
        ];
    }

    /**
     * Return the links related to this model.
     *
     * @return array
     */
    public function links(): array
    {
        return [
            'backend' => [
                'show' => [
                    'xx' => 'show',
                ],
            ],
        ];
    }
}
