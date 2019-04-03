<?php
/**
 * Created by PhpStorm.
 * User: Lloric Mayuga Garcia <lloricode@gmail.com>
 * Date: 12/3/18
 * Time: 11:16 AM
 */

namespace HalcyonLaravel\Base\Tests\Repositories;

use HalcyonLaravel\Base\Repository\BaseRepository;
use HalcyonLaravel\Base\Tests\App\Repositories\Observer;
use HalcyonLaravel\Base\Tests\Models\Core\Page;

class PageObserverRepositoryEloquent extends BaseRepository implements PageObserverRepository
{

    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return Page::class;
    }

    protected function observer()
    {
        return Observer::class;
    }
}