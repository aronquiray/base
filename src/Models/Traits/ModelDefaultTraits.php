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
            'backend' => [
                'show' 		=> [
                    'type' => 'show',
                    'perminssion' =>self::permission('show'),
                    'url' => route(self::ROUTE_ADMIN_PATH.'.show', $this)
                ],
                'edit' 		=> [
                    'type' => 'edit',
                    'perminssion' =>self::permission('edit'),
                    'url' => route(self::ROUTE_ADMIN_PATH.'.edit', $this)
                ],
                'destroy' 	=> [
                    'type' => 'destroy',
                    'perminssion' =>self::permission('destroy'),
                    'url' => route(self::ROUTE_ADMIN_PATH.'.destroy', $this),
                    'group' => 'more',
                    'redirect' => route(self::ROUTE_ADMIN_PATH.'.index')
                ],
            ]
               
        ];

        if (method_exists($this, 'bootSoftDeletes')) {
            $links['backend']['restore'] = [
                    'type' => 'restore',
                    'perminssion' =>self::permission('restore'),
                    'url' => route(self::ROUTE_ADMIN_PATH.'.restore', $this),
                    // 'group' => 'more',
                    'redirect' => route(self::ROUTE_ADMIN_PATH.'.index')
                ];
                
            $links['backend']['purge' ] = [
                    'type' => 'purge',
                    'perminssion' =>self::permission('purge'),
                    'url' => route(self::ROUTE_ADMIN_PATH.'.purge', $this),
                    // 'group' => 'more',
                    'redirect' => route(self::ROUTE_ADMIN_PATH.'.index')
                ];
        }
        return $links;
    }
}
