<?php
/**
 * Created by PhpStorm.
 * User: Lloric Mayuga Garcia <lloricode@gmail.com>
 * Date: 12/10/18
 * Time: 10:39 AM
 */

namespace HalcyonLaravel\Base\Database\Traits;

use HalcyonLaravel\Base\Models\Contracts\BaseModel;
use Illuminate\Http\UploadedFile;

trait SeederHelper
{
    /**
     * @param BaseModel $modelClass
     * @param bool $isAddToAdminRole
     * @param array $except
     */
    public function seederPermission(BaseModel $modelClass, bool $isAddToAdminRole = true, array $except = [])
    {
        $roleModel = resolve(config('permission.models.role'));

        $config = config('access.users');
        foreach ($modelClass::permissions() as $permissionName) {
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

    public function seederUploader($model, $file, array $customProperties = null, $collectionName = 'images')
    {
        if (is_string($file)) {
            if (!empty($customProperties)) {
                $model
                    ->copyMedia(testFilePath($file))
                    ->withCustomProperties($customProperties)
                    ->toMediaCollection($collectionName);

            } else {
                $model
                    ->copyMedia(testFilePath($file))
                    ->toMediaCollection($collectionName);
            }
        } elseif (get_class($file) instanceof UploadedFile) {
            if (!empty($customProperties)) {
                $model
                    ->addMedia($file)
                    ->withCustomProperties($customProperties)
                    ->preservingOriginal()
                    ->toMediaCollection($collectionName);

            } else {
                $model
                    ->addMedia($file)
                    ->preservingOriginal()
                    ->toMediaCollection($collectionName);
            }
        }
    }

}