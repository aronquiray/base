<?php

namespace App\Http\Controllers\Backend\Core\Page;

use Illuminate\Http\Request;
use HalcyonLaravel\Base\Controllers\BaseController as Controller;
use HalcyonLaravel\Base\Repository\BaseRepository as Repository;
use App\Models\Core\Page as Model;
use DataTables;

/**
 * Class PageTableController.
 */
class PagesTableController extends Controller
{
    /**
     * @var BlockRepository
     */
    protected $repo;

    /**
     * @param BlockRepository $repo
     */
    public function __construct()
    {
        $this->repo = new  Repository(new Model);
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
        return DataTables::of($this->repo->table([
                'trashOnly' => $trashOnly,
        ]))
            ->escapeColumns(['id'])
            ->editColumn('status', function ($model) use ($user) {
                return [
                    'type' => $model->status == "enable" ? 'success' : 'danger',
                    'label' => ucfirst($model->status),
                    'value' => $model->status,
                    'link' => route('admin.page.mark', $model),
                    'can' => $user->can('page change status')
                ];
            })
            ->editColumn('updated_at', function ($model) {
                return $model->updated_at->format('d M, Y h:m A');
            })
            ->addColumn('actions', function ($model) {
                return $model->actions('backend');
            })
            ->make(true);
    }
}
