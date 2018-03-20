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
                'show' 		=> [ 'type' => 'show', 'url' => route("{$this->route_admin_path}.show", $this) ],
                'edit' 		=> [ 'type' => 'edit', 'url' => route("{$this->route_admin_path}.edit", $this) ],
                'destroy' 	=> [ 'type' => 'destroy', 'url' => route("{$this->route_admin_path}.destroy", $this), 'group' => 'more', 'redirect' => route("$this->route_admin_path.index") ],

            ]
        ];
    }
}
