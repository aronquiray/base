<?php

namespace HalcyonLaravel\Base\Models\Traits;

trait ModelDefaultTraits
{
    /**
     * Return the links related to this model.
     *
     * @return array
     */
    public function links() : array
    {
        $links =  [
            'frontend' => [
                // 'show' => ['type' => 'show', 'url' => route("{$this->route_frontend_path}.show", $this)],
            ],
            'backend' => [
                'show' 		=> [
                    'type' => 'show',
                    'url' => route(self::routeAdminPath.'.show', $this)
                ],
                'edit' 		=> [
                    'type' => 'edit',
                    'url' => route(self::routeAdminPath.'.edit', $this)
                ],
                'destroy' 	=> [
                    'type' => 'destroy',
                    'url' => route(self::routeAdminPath.'.destroy', $this),
                    'group' => 'more',
                    'redirect' => route(self::routeAdminPath.'.index')
                ],
            ]
               
        ];

        if (method_exists(app(get_class($this)), 'bootSoftDeletes')) {
            $links['backend']['restore'] = [
                    'type' => 'restore',
                    'url' => route(self::routeAdminPath.'.restore', $this),
                    // 'group' => 'more',
                    'redirect' => route(self::routeAdminPath.'.index')
                ];
                
            $links['backend']['purge' ] = [
                    'type' => 'purge',
                    'url' => route(self::routeAdminPath.'.purge', $this),
                    // 'group' => 'more',
                    'redirect' => route(self::routeAdminPath.'.index')
                ];
        }
        return $links;
    }




    public function additionalLinks() : array
    {
        return [];
    }
}
