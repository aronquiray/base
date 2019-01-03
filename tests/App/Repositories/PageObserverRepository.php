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

class PageObserverRepository extends BaseRepository
{
    public function __construct()
    {
        parent::__construct();
        $this->setObserver(new Observer);
    }

    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return Page::class;
    }
}