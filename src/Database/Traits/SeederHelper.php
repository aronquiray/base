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
use Spatie\MediaLibrary\HasMedia\HasMedia;

trait SeederHelper
{
    /**
     * @param \HalcyonLaravel\Base\Models\Contracts\BaseModel $modelClass
     * @param bool                                            $isAddToAdminRole
     * @param array                                           $except
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

    /**
     * @param \Spatie\MediaLibrary\HasMedia\HasMedia $model
     * @param                                        $file
     * @param array|null                             $customProperties
     * @param string                                 $collectionName
     */
    public function seederUploader(HasMedia $model, $file, array $customProperties = null, $collectionName = 'images')
    {
        if (is_string($file)) {
            $fileName = explode('/', $file);
            $fileName = $fileName[count($fileName) - 1];

        } else {
            $fileName = $file->getClientOriginalName();
        }

        $fileName = explode('.', $fileName)[0];
        $fileName = str_replace('%20', ' ', $fileName);

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