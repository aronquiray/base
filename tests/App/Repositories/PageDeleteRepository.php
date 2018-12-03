<?php
/**
 * Created by PhpStorm.
 * User: Lloric Mayuga Garcia <lloricode@gmail.com>
 * Date: 12/3/18
 * Time: 11:16 AM
 */

namespace App\Repositories;

use App\Models\Core\PageSoftDelete;
use HalcyonLaravel\Base\Repository\BaseRepository;

class PageDeleteRepository extends BaseRepository
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