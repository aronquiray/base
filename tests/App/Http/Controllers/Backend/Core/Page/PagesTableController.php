<?php

namespace HalcyonLaravel\Base\Tests\Http\Controllers\Backend\Core\Page;

use DataTables;
use HalcyonLaravel\Base\Controllers\BaseController as Controller;
use HalcyonLaravel\Base\Repository\BaseRepositoryInterface;
use HalcyonLaravel\Base\Tests\Repositories\PageRepository;
use Illuminate\Http\Request;

/**
 * Class PageTableController.
 */
class PagesTableController extends Controller
{
    protected $pageRepository;

    /**
     * PagesTableController constructor.
     * @param PageRepository $pageRepository
     */
    public function __construct(PageRepository $pageRepository)
    {
        $this->pageRepository = $pageRepository;

        $m = $pageRepository->model();
        $model = new $m;

        //for testing only
        $permissionMiddleware = implode(',', $model->permission(['index']));
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

        return DataTables::of($this->pageRepository->table([
            'trashOnly' => $trashOnly,
        ]))->editColumn('status', function ($model) use ($user) {
            return [
                'type' => $model->status == "enable" ? 'success' : 'danger',
                'label' => ucfirst($model->status),
                'value' => $model->status,
                'link' => route('admin.page.status', $model),
                'can' => $user->can('page change status'),
            ];
        })->editColumn('updated_at', function ($model) {
            return $model->updated_at->format('d M, Y h:m A');
        })->addColumn('actions', function ($model) {
            return $model->actions('backend');
        })->make(true);
    }

    public function repository(): BaseRepositoryInterface
    {
        return $this->pageRepository;
    }
}
