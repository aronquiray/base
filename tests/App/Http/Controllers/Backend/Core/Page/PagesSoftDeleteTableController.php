<?php

namespace App\Http\Controllers\Backend\Core\Page;

use App\Repositories\PageDeleteRepository;
use DataTables;
use HalcyonLaravel\Base\Controllers\BaseController as Controller;
use HalcyonLaravel\Base\Repository\BaseRepository as Repository;
use Illuminate\Http\Request;

/**
 * Class PageTableController.
 */
class PagesSoftDeleteTableController extends Controller
{
    protected $pageDeleteRepository;

    /**
     * @param BlockRepository $repo
     */
    public function __construct(PageDeleteRepository $pageDeleteRepository)
    {
        $this->pageDeleteRepository = $pageDeleteRepository;
        // $this->middleware('permission:content list,content activity,content delete', ['only' => ['__invoke']]);
    }

    /**
     * @param Request $request
     *
     * @return mixed
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
