<?php

namespace HalcyonLaravel\Base\Controllers\Backend\Traits;

trait CRUDTrait
{
    /**
     * @param $submission
     * @param $args
     * @return string
     */
    protected function _redirectAfterAction($submission, $args): string
    {
        if (! is_null($submission) && strpos($submission, 'http') !== false) {
            return $submission;
        }
        $submission = $submission ?? 'show';

        return route($this->route_path.'.'.$submission, $args);
    }
}
