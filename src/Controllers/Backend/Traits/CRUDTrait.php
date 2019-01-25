<?php

namespace HalcyonLaravel\Base\Controllers\Backend\Traits;

trait CRUDTrait
{
    /**
     * @param        $submission
     * @param        $args
     * @param string $queryParams
     *
     * @return string
     */
    protected function _redirectAfterAction($submission, $args, string $queryParams = ''): string
    {
        if (!is_null($submission) && strpos($submission, 'http') !== false) {
            return $submission;
        }
        $submission = $submission ?? 'show';

        return route($this->routePath . '.' . $submission, $args) . $queryParams;
    }
}
