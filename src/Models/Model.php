<?php

namespace HalcyonLaravel\Base\Models;

use Illuminate\Database\Eloquent\Model as BaseModel;
use HalcyonLaravel\Base\Models\Contracts\ModelContract;
use HalcyonLaravel\Base\Models\Traits\ModelHelpers;
/**
 * Class Model.
 */
abstract class Model extends BaseModel implements ModelContract
{
	use ModelHelpers;

	/**
     * Module Name
     * 
     * @return String
     */
    public $module_name;

    /**
     * Path of the module for the crud backend
     * 
     * @return String
     */
    public $view_backend_path;

    /**
     * Path of the module for the crud frontend
     * 
     * @return String
     */
    public $view_frontend_path;

    /**
     * Path of the module for the admin route
     * 
     * @return String
     */
    public $route_admin_path;

    /**
     * Path of the module for the frontend route
     * 
     * @return String
     */
    public $route_frontend_path;
}
