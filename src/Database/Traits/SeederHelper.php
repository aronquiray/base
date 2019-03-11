<?php
/**
 * Created by PhpStorm.
 * User: Lloric Mayuga Garcia <lloricode@gmail.com>
 * Date: 12/10/18
 * Time: 10:39 AM
 */

namespace HalcyonLaravel\Base\Database\Traits;

use App\Models\Core\Page\Page;
use HalcyonLaravel\Base\Models\Contracts\BaseModelInterface;
use HalcyonLaravel\Base\Models\Contracts\BaseModelPermissionInterface;
use HalcyonLaravel\Base\Models\Model;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;
use Schema;
use Spatie\MediaLibrary\HasMedia\HasMedia;

trait SeederHelper
{

    /**
     * @param \HalcyonLaravel\Base\Models\Model $model
     *
     * @return \App\Models\Core\Page\Page
     */
    public function modelPageSeeder(Model $model): Page
    {
        $tableName = (new Page)->getTable();

        $prepare = [
            'title' => ucfirst($model::MODULE_NAME),
            'pageable_type' => get_class($model),
        ];

        foreach (array_keys($prepare) as $column => $value) {
            if (!Schema::hasColumn($tableName, $column)) {
                unset($prepare[$column]);
            }
        }

        $page = Page::create($prepare);
        $page->metaTag()->create([
            'title' => ucfirst($model::MODULE_NAME),
            'description' => 'List of all ' . Str::plural($model::MODULE_NAME),
            'keywords' => 'page,' . str_replace('-', ',', Str::kebab($model::MODULE_NAME)),
        ]);
        return $page;
    }

    /**
     * @param \HalcyonLaravel\Base\Models\Contracts\BaseModelPermissionInterface $modelClass
     * @param bool                                                               $isAddToAdminRole
     * @param array                                                              $except
     */
    public function seederPermission(
        BaseModelPermissionInterface $modelClass,
        bool $isAddToAdminRole = true,
        array $except = []
    ) {
        $this->permissionStore($modelClass, $isAddToAdminRole, $except);
    }

    /**
     * @param       $modelClassOrArray
     * @param bool  $isAddToAdminRole
     * @param array $except
     */
    private function permissionStore($modelClassOrArray, bool $isAddToAdminRole = true, array $except = [])
    {

        $permissionNames = is_array($modelClassOrArray) ? $modelClassOrArray : $modelClassOrArray::permissions();

        $roleModel = resolve(config('permission.models.role'));

        $config = config('access.users');
        foreach ($permissionNames as $permissionName) {
            $permission = resolve(config('permission.models.permission'))::create([
                'name' => $permissionName,
            ]);
            $roleModel::findByName($config['system_role'])->givePermissionTo($permission);
            if ($isAddToAdminRole) {
                if (!in_array($permissionName, $except)) {
                    $roleModel::findByName($config['admin_role'])->givePermissionTo($permission);
                }
            }
        }
    }

    /**
     * @param array $permissionName
     * @param bool  $isAddToAdminRole
     * @param array $except
     */
    public function seederPermissionArray(array $permissionName, bool $isAddToAdminRole = true, array $except = [])
    {
        $this->permissionStore($permissionName, $isAddToAdminRole, $except);
    }

    /**
     * @param \Spatie\MediaLibrary\HasMedia\HasMedia $model
     * @param                                        $file
     * @param array|null                             $customProperties
     * @param string                                 $collectionName
     */
    public function seederUploader(HasMedia $model, $file, array $customProperties = null, $collectionName = 'images')
    {
        if ($model instanceof BaseModelInterface) {
            $fileName = $model->base();
        } else {
            if (is_string($file)) {
                $fileName = explode('/', $file);
                $fileName = $fileName[count($fileName) - 1];

            } else {
                $fileName = $file->getClientOriginalName();
            }
            $fileName = explode('.', $fileName)[0];
            $fileName = str_replace('%20', ' ', $fileName);
            $fileName = str_replace('-', ' ', $fileName);
            $fileName = str_replace('_', ' ', $fileName);
        }

        $customProperties = array_merge([
            'attributes' => [
                'title' => $fileName,
                'alt' => $fileName,
            ],
        ], $customProperties ?: []);

        if (is_string($file)) {
            $model
                ->copyMedia(testFilePath($file))
                ->withCustomProperties($customProperties)
                ->toMediaCollection($collectionName);


        } elseif (get_class($file) instanceof UploadedFile) {

            $model
                ->addMedia($file)
                ->withCustomProperties($customProperties)
                ->preservingOriginal()
                ->toMediaCollection($collectionName);


        }
    }

}