<?php

namespace HalcyonLaravel\Base\Controllers\Backend\Traits;

trait CRUDTrait
{
    /**
     * @param \HalcyonLaravel\Base\Controllers\Backend\Traits\string|null $submission
     * @param $args
     * @return string
     */
    protected function _redirectAfterAction(string $submission = null, $args): string
    {
        if (! is_null($submission) && strpos($submission, 'http') !== false) {
            return $submission;
        }
        $submission = $submission ?? 'show';

        return route($this->route_path.'.'.$submission, $args);
    }
}
