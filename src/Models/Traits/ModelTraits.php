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
    public function actions(string $group, array $keys = null) : array
    {
        $user = auth()->user();
        
        $links = array_merge($this->links(), $this->additionalLinks())[$group];

        foreach ($links as $l => $link) {
            if (
                (array_key_exists('permission', $link) && ! $user->can($link['permission'])) ||
                (! is_null($keys) && ! in_array($l, $keys))
            ) {
                array_forget($links, $l);
            }
        }

        return $links;
    }
}
