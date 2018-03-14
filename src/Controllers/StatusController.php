<?php

namespace HalcyonLaravel\Base\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

/**
 * Class StatusController.
 */
class StatusController extends Controller
{
    public function inactive()
    {
        return view($this->view . '.inactive');
    }

    public function mark(Request $request, $slug)
    {
        $model = $this->model->whereSlug($slug)->firstOrFail();
        $model = $this->repo->mark($request, $model);
        return $this->response('mark', $request, $model);
    }
   
    public function response($process, $request, $model=null)
    {
        $message 	= 'You have successfully changed the status for ' . $model->name . '.';
        $route = route($this->route . '.index');
        return $request->ajax() ? response()->json(['message' => $message, 'link' => $route]) : redirect($route)->withFlashSuccess($message);
    }
}
