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
    protected $module_name;

    /**
     * Path of the module for the crud backend
     * 
     * @return String
     */
    public function view_backend_path() { return "backend.$this->module_name"; }

    /**
     * Path of the module for the crud frontend
     * 
     * @return String
     */
    public function view_frontend_path() { return "frontend.$this->module_name"; }

    /**
     * Path of the module for the admin route
     * 
     * @return String
     */
    public function route_admin_path() { return "admin.$this->module_name"; }

    /**
     * Path of the module for the frontend route
     * 
     * @return String
     */
    public function route_frontend_path() { return "frontend.$this->module_name"; }

    
}
