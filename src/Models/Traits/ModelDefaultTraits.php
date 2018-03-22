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
        return [
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
                    'url' => route(
 
                        self::routeAdminPath.'.edit',
                    $this
 
                    )
                ],
                'destroy' 	=> [
                    'type' => 'destroy',
                    'url' => route(self::routeAdminPath.'.destroy', $this),
                    'group' => 'more',
                    'redirect' => route(self::routeAdminPath.'.index')
                 ],
            ]
        ];
    }
}
