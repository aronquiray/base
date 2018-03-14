<?php

namespace HalcyonLaravel\Base\Traits;

trait Baseable
{
    /**
     * Return the baseable configuration array for this model.
     *
     * @return array
     */
    abstract public function baseable(): array;


    /**
     * Returns the value of a given key in the baseable function
     *
     * @return mixed
     */
    public function base($key)
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
     * Return the action at a given key.
     * @param bool $type : frontend | backend
     * @param String $key
     * @return String $action
     */
    public function action($type, $key): String
    {
        if (! method_exists($this, 'actions')) {
            return '#';
        }
        return array_key_exists($key, $this->actions($type)) ? $this->actions($type)[$key]['link'] : '#';
    }
}
