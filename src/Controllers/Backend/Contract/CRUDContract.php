<?php

namespace HalcyonLaravel\Base\Controllers\Backend\Contract;

use Illuminate\Http\Request;

interface CRUDContract 
{
	/**
     * @param Request $request
     * @param Model $model | nullable
     * 
     * @return array 
     */
    public function generateStub(Request $request) : array;

    /**
     * Validate input on store 
     * 
     * @return array 
     */
    public function storeRules(Request $request) : array;
    
    /**
     * Validate input on update 
     * 
     * @param Model $model | nullable
     * 
     * @return array 
     */
    public function updateRules(Request $request, $model) : array;
}