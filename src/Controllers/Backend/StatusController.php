<?php

namespace HalcyonLaravel\Base\Controllers\Backend;

use Illuminate\Http\Request;
use HalcyonLaravel\Base\Controllers\BaseController as Controller;

/**
 * Class StatusController.
 */
class StatusController extends Controller
{
    use CRUDTraits;

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function inactive()
    {
        return view("$this->view_path.inactive");
    }

    /**
     * @param Request $request
     * @param String $routeKeyName
     * 
     * @return $response
     */
    public function mark(Request $request, String $routeKeyName)
    {
        $model = $this->getModel($routeKeyName);
        $this->repo->mark($request, $model);
        $redirect = route("$this->route_path." . ($this->modelIsActive($model) ? 'index' : 'inactive') ) ;
        $message = __("base::actions.mark.", ['Name' => $this->getName($model), 'status' => ($this->modelIsActive($model) ? 'enabled' : 'disabled')])
        return $this->response('mark', $request, $model, $redirect);
    }

    /**
     * @param Request $request
     * @param String $routeKeyName
     * 
     * @return $response
     */
    abstract public function modelIsActive(Model $model) : bool; 

}
