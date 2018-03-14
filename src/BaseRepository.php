<?php
namespace HalcyonLaravel\Base;

use DB;
use PackageHalcyon\History\Facades\History;

use Illuminate\Pagination\Paginator;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use PackageHalcyon\Uploader\Facades\Uploader;

class BaseRepository
{
    /**
     * @param Request $request
     * @param Model  $model
     *
     * @return static
     */
    public function mark($request, $model)
    {
        DB::beginTransaction();
        try {
            $model->update(['status' => ($request->status == 'true' ? 'active' : 'inactive') ]);
            History::updated($model, $model);
            DB::commit();
            return $model;
        } catch (\Exception $e) {
            DB::rollback();
            dd($e);
        }
    }
    /**
     * @param Model $model
     *
     * @throws GeneralException
     *
     * @return bool
     */
    public function destroy($model)
    {
        DB::transaction(function () use ($model) {
            $this->_deleting($model);
            if ($model->delete()) {
                History::deleted($model);
                return true;
            }
            $this->exceptions();
        });
    }

    /**
     * @param Model $user
     *
     * @throws GeneralException
     *
     * @return bool
     */
    public function restore($model)
    {
        if (is_null($model->deleted_at)) {
            $this->exceptions('This content has not been deleted yet.');
        }

        if ($model->restore()) {
            History::restored($model);
            return true;
        }
    }

    /**
     * @param Model $user
     *
     * @throws GeneralException
     */
    public function purge($model)
    {
        if (is_null($model->deleted_at)) {
            $this->exceptions('This content has not been deleted yet.');
        }

        DB::transaction(function () use ($model) {
            $this->_purging($model);
            if ($model->forceDelete()) {
                History::purged($model);
                return true;
            }

            $this->exceptions($this);
        });
    }

    /**
     * @return GeneralException
     */
    public function exceptions($label)
    {
        throw new \Exception($label);
    }

    /**
      * Laravel Paginate Collection or Array.
      *
      * @param array|Collection      $items
      * @param int   $perPage
      * @param int  $page
      * @param array $options
      *
      * @return LengthAwarePaginator
      */
    protected function paginate($items, $perPage = 15, $page = null, $options = [])
    {
        $page = $page ?: (Paginator::resolveCurrentPage() ?: 1);
        $items = $items instanceof Collection ? $items : Collection::make($items);
        return new LengthAwarePaginator($items->forPage($page, $perPage), $items->count(), $perPage, $page, $options);
    }


    /**
      * Handle Uploading Images
      *
      * @param Model $model
      * @param Request $request
      *
      * @return Model $model
      */
    protected function _handleImages($model, $image)
    {
        if ($model->image && isset($image['image_remove']) && $image['image_remove'] == true) {
            Uploader::destroy($model->image);
            $model->update(['image' => null]);
        }
        if (isset($image['image'])) {
            $this->imageConfig['name'] = $model->id;
            $this->imageConfig['path'] = $this->imageConfig['path'] . '/' . $model->id;
            $path = Uploader::store($image['image'], $this->imageConfig);
            if ($image['image'] && Uploader::checkIfPathExists($image['image'])) {
                Uploader::destroy($image['image']);
            }
            $model->update(['image' => $path]);
        }
        return $model;
    }

    /**
     * Calls event before deleting
     * @param Model $model
     */
    protected function _deleting($model)
    {
    }

    /**
     * Calls event before permanently deleting
     * @param Model $model
     */
    protected function _purging($model)
    {
    }
}
