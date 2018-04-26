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
                    'perminssion' => $this->permission('show'),
                    'url' => route(self::routeAdminPath.'.show', $this)
                ],
                'edit' 		=> [
                    'type' => 'edit',
                    'perminssion' => $this->permission('edit'),
                    'url' => route(self::routeAdminPath.'.edit', $this)
                ],
                'destroy' 	=> [
                    'type' => 'destroy',
                    'perminssion' => $this->permission('destroy'),
                    'url' => route(self::routeAdminPath.'.destroy', $this),
                    'group' => 'more',
                    'redirect' => route(self::routeAdminPath.'.index')
                ],
            ]
               
        ];

        if (method_exists($this, 'bootSoftDeletes')) {
            $links['backend']['restore'] = [
                    'type' => 'restore',
                    'perminssion' => $this->permission('restore'),
                    'url' => route(self::routeAdminPath.'.restore', $this),
                    // 'group' => 'more',
                    'redirect' => route(self::routeAdminPath.'.index')
                ];
                
            $links['backend']['purge' ] = [
                    'type' => 'purge',
                    'perminssion' => $this->permission('purge'),
                    'url' => route(self::routeAdminPath.'.purge', $this),
                    // 'group' => 'more',
                    'redirect' => route(self::routeAdminPath.'.index')
                ];
        }
        return $links;
    }
}
