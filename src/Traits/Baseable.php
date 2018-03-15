<?php

namespace HalcyonLaravel\Base\Traits;

use Illuminate\Database\Eloquent\Model;

trait Baseable
{
    /**
     * The module class.
     *
     * @var \Illuminate\Database\Eloquent\Model
     */
    protected $model;

    /**
     * Specify Model class name.
     *
     * @return mixed
     */
    abstract public function model();

    /**
     * @return Model|mixed
     * @throws GeneralException
     */
    public function makeModel()
    {
        $model = app()->make($this->model());

        if (! $model instanceof Model) {
            throw new GeneralException("Class {$this->model()} must be an instance of ". Model::class);
        }

        return $this->model = $model;
    }
}
