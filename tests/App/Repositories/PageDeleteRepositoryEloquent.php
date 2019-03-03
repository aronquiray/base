<?php
/**
 * Created by PhpStorm.
 * User: Lloric Mayuga Garcia <lloricode@gmail.com>
 * Date: 12/3/18
 * Time: 11:16 AM
 */

namespace HalcyonLaravel\Base\Tests\Repositories;

use HalcyonLaravel\Base\Repository\BaseRepository;
use HalcyonLaravel\Base\Tests\Models\Core\PageSoftDelete;

class PageDeleteRepositoryEloquent extends BaseRepository implements PageDeleteRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return PageSoftDelete::class;
    }
}