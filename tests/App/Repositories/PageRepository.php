<?php
/**
 * Created by PhpStorm.
 * User: Lloric Mayuga Garcia <lloricode@gmail.com>
 * Date: 12/3/18
 * Time: 11:16 AM
 */

namespace App\Repositories;

use App\Models\Core\Page;
use HalcyonLaravel\Base\Repository\BaseRepository;

class PageRepository extends BaseRepository
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
}