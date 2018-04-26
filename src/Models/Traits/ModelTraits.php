<?php

namespace HalcyonLaravel\Base\Models\Traits;

trait ModelTraits
{
    /**
      * Returns the value of a given key in the baseable function
      *
      * @param string $key
      *
      * @return mixed
      */
    public function base(string $key) : string
    {
        $config = $this->baseable();
        if (array_key_exists($key, $config)) {
            $key = $config[$key];
        } else {
            $key = $config['source'];
        }
        return $this->$key;
    }


    /**
     * Returns the list of links within the selected group
     *
     * @param string $group
     *
     * @return array $links
     */
    public function actions(string $group, $keys = null, bool $onlyLinks = false)
    {
        $user = auth()->user();
        
        if (method_exists($this, 'additionalLinks')) {
            $links = array_merge($this->links(), $this->additionalLinks())[$group];
        } else {
            $links = $this->links()[$group];
        }

        foreach ($links as $l => $link) {
            if (
                (array_key_exists('permission', $link) && $user && ! $user->can($link['permission'])) ||
                (! is_null($keys) && is_array($keys) && ! in_array($l, $keys)) ||
                (! is_null($keys) && ! is_array($keys) && $keys != $l)
            ) {
                array_forget($links, $l);
            }
        }

        if ($onlyLinks == true) {
            $filter = [];
            foreach ($links as $l => $link) {
                $filter[$l] = $link['url'];
            }
            $links = $filter;
        }
        if (! is_null($keys) && is_string($keys) && count($links) == 1) {
            return $links[$keys];
        }
        return $links;
    }

    /**
     * Returns the list of permissoin for this group.
     *
     * @param mixed $keys
     *
     * @return array $permissions
     */
    public function permission($keys = null)
    {
        $permissions = $this->permissions();
        if (! is_null($keys) && is_array($keys)) {
            foreach ($permissions as $p => $permission) {
                if (! in_array($p, $keys)) {
                    array_forget($permissions, $p);
                }
            }
            return $permissions;
        }
        return $permissions[$keys];
    }
}
