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
     * @param array $data
     * 
     * @return QueryBuilder $query
     */
    public function table(array $request) : Builder
    {
        return $this->model->query();
    }
    /**
     | ------------------------------------------------------------
     |   
     |                  CRUD Actions
     |
     | ------------------------------------------------------------
     */

    /**
      * This will handle DB transaction actiosn
      * 
      * @param String $closure
      * @return mixed $data
      * @throws Exception $e
      */
    public function action($closure)
    {
        try {
            DB::beginTransaction();
            $data = $closure();
            DB::commit();
            return $data;
        } catch (\Exception $e) {
            DB::rollback();
            $this->_handleErrors($e);
        }
    }

    /**
     * Handle inaccessible methods
     */
    public function __call($name, $args)
    {
        if (in_array($name, [
            'storing', 'stored',
            'updating', 'updated',
            'deleting', 'deleted',
            'restoring', 'restored',
            'purging', 'purged',
        ])) {
            return $args;
        }
    }

    /**
     * Handle exception errors
     * @param Exception|String $e
     * @throws Exception $message
     */
    private function _handleErrors($e)
    {
        $message = $e instanceOf \Exception ? $e->getMessage() : $e;
        throw new \Exception($message);
    }

    /**
     * @param array|mixed
     * @return mixed
     * @throws \Exception
     */
    public function store($data)
    {
        return $this->action(function () use ( $data ) {
            $data = $this->storing($data);
            $model = $model->store($data);
            return $this->stored($data, $model);
        });
    }

    /**
     * @param array|mixed
     * @return mixed
     * @throws Exception
     */
    public function update($data, $model)
    {
        return $this->action(function () use ( $data, $model ) {
            $model = $this->updating($data, $model);
            $model->update($data);
            return $this->updated($data, $model);
        });
    }


    /**
     * @param Model $model
     *
     * @throws \Exception
     * @return $model
     */
    public function destroy($model)
    {
        return $this->action(function () use ( $model ) {
            $model = $this->deleting($model);
            $model->delete();
            return $this->deleted($model);
        });
    }



    /**
     * @param Request $request
     * @param Model  $model
     *
     * @return mixed
     */
    public function mark($data, $model)
    {
        return $this->action(function () use ( $data, $model ) {
            $model = $this->updating($data, $model);
            $model->update($data);
            return $this->updated($data, $model);
        });
    }

    /**
     * @param Model $model
     *
     * @throws Exception
     *
     * @return mixed
     */
    public function restore($model)
    {
        if (is_null($model->deleted_at)) {
            $this->_handleErrors('This content has not been deleted yet.');
        }

        return $this->action(function () use ( $model ) {
            $model = $this->restoring($model);
            $model->restore();
            return $this->restored($model);
        });
    }

    /**
     * @param Model $model
     *
     * @throws Exception
     * @return mixed
     */
    public function purge($model)
    {
        if (is_null($model->deleted_at)) {
            $this->_handleErrors('This content has not been deleted yet.');
        }

        return $this->action(function () use ( $model ) {
            $model = $this->purging($model);
            $model->forceDelete();
            return $this->purged($model);
        });
    }
}
