<?php

namespace HalcyonLaravel\Base\Models\Traits;

trait ModelTraits
{
    /**
      * Returns the value of a given key in the baseable function
      *
      * @param String $key
      *
      * @return mixed
      */
    public function base(string $key) : string
    {
        $config = $this->baseable();
        if (! is_array($config)) {
            $config = ['source' => $this->baseable()];
        }
        if (array_key_exists($key, $config)) {
            return $config[$key];
        }
        $key = $config['source'];
        return $this->$key;
    }


    /**
     * Returns the list of links within the selected group
     *
     * @param String $group
     *
     * @return array $links
     */
    public function actions(string $group, array $keys = null) : array
    {
        $user = auth()->user();
        $links = $this->links()[$group];

        foreach ($links as $l => $link) {
            if (
                (array_key_exists('permission', $link) && ! $user->can($link['permission'])) ||  
                (is_null($keys) && ! in_array($l, $keys))
            ) {
                array_forget($links, $l);
            }
            
        }

        return $links;
    }


    /**
     * Return the action at a given group and key.
     * @param string $group
     * @param string $key
     *
     * @return string $action
     */
    public function action($group, $key): string
    {
        if (! method_exists($this, 'actions')) {
            return '#';
        }
        return array_key_exists($key, $this->actions($group)) ? $this->actions($group)[$key]['link'] : '#';
    }
}
