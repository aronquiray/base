<?php
namespace HalcyonLaravel\Base\Repository;

use DB;
use Illuminate\Database\Eloquent\Model;
use HalcyonLaravel\Base\Traits\Baseable;

class BaseRepository
{
    /**
     * @param Model $model
     */
    protected $model;

    /**
     * BaseRepository Constructor
     */
    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    /**
     | ------------------------------------------------------------
     |   
     |                  CRUD Actions
     |
     | ------------------------------------------------------------
     */

    public function actions($closure)
    {
        DB::beginTransaction();
        try {
            $model = $closure();
            DB::commit();
            return $model;
        } catch (\Exception $e) {
            DB::rollback();
            throw new \Exception($e->getMessage());
        }
    }



    /**
     * @param array|mixed
     * @return Model|mixed
     * @throws \Exception
     */
    public function store($data)
    {
        return $this->actions(function () use ( $data ) {
            return $this->model->create($data);
        });
    }

    /**
     * @param array|mixed
     * @return Model|mixed
     * @throws Exception
     */
    public function update($data, $model)
    {
        return $this->actions(function () use ( $data, $model ) {
            return $model->update($data);
        });
    }


    /**
     * @param Model $model
     *
     * @throws \Exception
     * @return bool
     */
    public function destroy($model)
    {
        return $this->actions(function () use ( $model ) {
            $model->delete();
            return $model;
        });
    }



    /**
     * @param Request $request
     * @param Model  $model
     *
     * @return static
     */
    public function mark($data, $model)
    {
        return $this->actions(function () use ( $data, $model ) {
            $model->update($data);
            return $model;
        });
    }

    /**
     * @param Model $user
     *
     * @throws Exception
     *
     * @return bool
     */
    public function restore($model)
    {
        if (is_null($model->deleted_at)) {
            throw new Exception('This content has not been deleted yet.');
        }

        return $this->actions(function () use ( $model ) {
            $model->restore();
            return $model;
        });
    }

    /**
     * @param Model $user
     *
     * @throws Exception
     */
    public function purge($model)
    {
        if (is_null($model->deleted_at)) {
            throw new Exception('This content has not been deleted yet.');
        }

        return $this->actions(function () use ( $model ) {
            $model->forceDelete();
            return $model;
        });
    }
}
