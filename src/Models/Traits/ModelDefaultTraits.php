<?php

namespace HalcyonLaravel\Base\Models\Traits;

use Route;

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
                    'url' => array(self::routeAdminPath.'.show', $this)
                ],
                'edit' 		=> [
                    'type' => 'edit',
                    'perminssion' => $this->permission('edit'),
                    'url' => array(self::routeAdminPath.'.edit', $this)
                ],
                'destroy' 	=> [
                    'type' => 'destroy',
                    'perminssion' => $this->permission('destroy'),
                    'url' => array(self::routeAdminPath.'.destroy', $this),
                    'group' => 'more',
                    'redirect' => route(self::routeAdminPath.'.index')
                ],
            ]
               
        ];

        if (method_exists($this, 'bootSoftDeletes')) {
            $links['backend']['restore'] = [
                    'type' => 'restore',
                    'perminssion' => $this->permission('restore'),
                    'url' => array(self::routeAdminPath.'.restore', $this),
                    // 'group' => 'more',
                    'redirect' => route(self::routeAdminPath.'.index')
                ];
                
            $links['backend']['purge' ] = [
                    'type' => 'purge',
                    'perminssion' => $this->permission('purge'),
                    'url' => array(self::routeAdminPath.'.purge', $this),
                    // 'group' => 'more',
                    'redirect' => array(self::routeAdminPath.'.index')
                ];
        }

        foreach ($links['backend'] as $type => $link) {
            if (!Route::has($link['url'][0])) {
                array_forget($links['backend'], $type);
                continue;
            }
            $links['backend'][$type]['url'] = route($link['url'][0], $link['url'][1]);
        }

        return $links;
    }
}
