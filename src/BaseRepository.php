<?php
namespace HalcyonLaravel\Base;

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

    /**
     * @param array|mixed
     * @return Model|mixed
     * @throws \GeneralException
     */
    public function store($data)
    {
        DB::beginTransaction();
        try {
            $model = $this->model->create($data);
            DB::commit();
            return $model;
        } catch (\Exception $e) {
            DB::rollback();
            throw new \GeneralException($e->getMessage());
        }
    }

    /**
     * @param array|mixed
     * @return Model|mixed
     * @throws GeneralException
     */
    public function update($data, $model)
    {
        DB::beginTransaction();
        try {
            $model = $model->update($data);
            DB::commit();
            return $model;
        } catch (\Exception $e) {
            DB::rollback();
            throw new \GeneralException($e->getMessage());
        }
    }


    /**
     * @param Model $model
     *
     * @throws \GeneralException
     * @return bool
     */
    public function destroy($model)
    {
        DB::transaction(function () use ($model) {
            $this->deleting($model);
            if ($model->delete()) {
                // History::deleted($model);
                return true;
            }
            throw new \GeneralException($this );
        });
    }
}
