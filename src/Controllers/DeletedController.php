<?php

namespace HalcyonLaravel\Base\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

/**
 * Class DeletedController.
 */
class DeletedController extends Controller
{
    public function deleted()
    {
        return view($this->view . '.deleted');
    }

    /**
     * @param Request $request, Model $block
     * @return $block
     */
    public function restore(Request $request, $slug)
    {
        $model = $this->model->withTrashed()->whereSlug($slug)->firstOrFail();
        $this->repo->restore($model);
        return $this->response('restore', $request, $model);
    }

    /**
     *
     * @param Request $request, Model $model
     * @return $response
     */
    public function purge(Request $request, $slug)
    {
        $model = $this->model->withTrashed()->whereSlug($slug)->firstOrFail();
        $this->repo->purge($model);
        return $this->response('purge', $request, $model);
    }
    

    public function response($process, $request, $model=null)
    {
        $message    = 'You have successfully ' . $process . 'd the ' . $model->name . '.';
        $route      = $this->route . '.index';
        return $request->ajax() ? response()->json(['message' => $message, 'link' => $route]) : redirect()->route($route)->withFlashSuccess($message);
    }
}
