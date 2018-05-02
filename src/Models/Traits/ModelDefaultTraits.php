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
                    'perminssion' =>self::permission('show'),
                    'url' => [self::ROUTE_ADMIN_PATH.'.show', $this]
                ],
                'edit' 		=> [
                    'type' => 'edit',
                    'perminssion' =>self::permission('edit'),
                    'url' => [self::ROUTE_ADMIN_PATH.'.edit', $this]
                ],
                'destroy' 	=> [
                    'type' => 'destroy',
                    'perminssion' =>self::permission('destroy'),
                    'url' => [self::ROUTE_ADMIN_PATH.'.destroy', $this],
                    'group' => 'more',
                    'redirect' => [self::ROUTE_ADMIN_PATH.'.index']
                ],
            ]
               
        ];

        if (method_exists($this, 'bootSoftDeletes')) {
            $links['backend']['restore'] = [
                    'type' => 'restore',
                    'perminssion' =>self::permission('restore'),
                    'url' => [self::ROUTE_ADMIN_PATH.'.restore', $this],
                    // 'group' => 'more',
                    'redirect' => [self::ROUTE_ADMIN_PATH.'.index']
                ];
                
            $links['backend']['purge' ] = [
                    'type' => 'purge',
                    'perminssion' =>self::permission('purge'),
                    'url' => [self::ROUTE_ADMIN_PATH.'.purge', $this],
                    // 'group' => 'more',
                    'redirect' => [self::ROUTE_ADMIN_PATH.'.index']
                ];
        }

        foreach ($links['backend'] as $type => $link) {
            if (!Route::has($link['url'][0])) {
                array_forget($links['backend'], $type);
                continue;
            }
            if (isset($links['backend'][$type]['redirect'])) {
                if (!Route::has($link['redirect'][0])) {
                    array_forget($links['backend'], $type);
                    continue;
                }
                $links['backend'][$type]['redirect'] = route($link['redirect'][0]);
            }
            $links['backend'][$type]['url'] = route($link['url'][0], $link['url'][1]);
        }

        return $links;
    }
}
