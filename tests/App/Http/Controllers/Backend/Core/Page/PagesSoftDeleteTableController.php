<?php

namespace HalcyonLaravel\Base\Tests\Http\Controllers\Backend\Core\Page;

use DataTables;
use HalcyonLaravel\Base\Controllers\BaseController as Controller;
use HalcyonLaravel\Base\Repository\BaseRepository as Repository;
use HalcyonLaravel\Base\Tests\Repositories\PageDeleteRepository;
use Illuminate\Http\Request;

/**
 * Class PageTableController.
 */
class PagesSoftDeleteTableController extends Controller
{
    protected $pageDeleteRepository;

    /**
     * PagesSoftDeleteTableController constructor.
     *
     * @param \HalcyonLaravel\Base\Tests\Repositories\PageDeleteRepository $pageDeleteRepository
     */
    public function __construct(PageDeleteRepository $pageDeleteRepository)
    {
        $this->pageDeleteRepository = $pageDeleteRepository;
        // $this->middleware('permission:content list,content activity,content delete', ['only' => ['__invoke']]);
    }

    /**
     * @param \Illuminate\Http\Request $request
     *
     * @return mixed
     * @throws \Prettus\Repository\Exceptions\RepositoryException
     */
    public function __invoke(Request $request)
    {
        $trashOnly = false;
        if ($request->trash) {
            $trashOnly = (bool) $request->trash;
        }

        $user = auth()->user();

        return DataTables::of($this->pageDeleteRepository->table([
            'trashOnly' => $trashOnly,
        ]))->editColumn('status', function ($model) use ($user) {
                return [
                    'type' => $model->status == "enable" ? 'success' : 'danger',
                    'label' => ucfirst($model->status),
                    'value' => $model->status,
                    'link' => route('admin.page-sd.status.update', $model),
                    'can' => $user->can('page change status'),
                ];
            })->editColumn('updated_at', function ($model) {
                return $model->updated_at->format('d M, Y h:m A');
            })->addColumn('actions', function ($model) {
                return $model->actions('backend');
            })->make(true);
    }

    public function repository(): Repository
    {
        return $this->pageDeleteRepository;
    }
}
