<?php
/**
 * Created by PhpStorm.
 * User: Lloric Mayuga Garcia <lloricode@gmail.com>
 * Date: 12/10/18
 * Time: 10:39 AM
 */

namespace HalcyonLaravel\Base\Database\Traits;

use HalcyonLaravel\Base\Models\Contracts\BaseModelInterface;
use HalcyonLaravel\Base\Models\Contracts\BaseModelPermissionInterface;
use HalcyonLaravel\Base\Models\Model;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use Spatie\MediaLibrary\HasMedia\HasMedia;

/**
 * Trait SeederHelper
 *
 * @package HalcyonLaravel\Base\Database\Traits
 * @codeCoverageIgnore
 */
trait SeederHelper
{

    /**
     * @param  \HalcyonLaravel\Base\Models\Model  $model
     *
     * @return mixed
     */
    public function modelPageSeeder(Model $model)
    {
        $pageModel = app(config('base.models.page'));
        $tableName = $pageModel->getTable();

        $prepare = [
            'title' => ucfirst($model::MODULE_NAME),
            'pageable_type' => get_class($model),
        ];

        foreach ($prepare as $column => $value) {
            if (!Schema::hasColumn($tableName, $column)) {
                unset($prepare[$column]);
            }
        }

        $page = $pageModel->create($prepare);
        $page->metaTag()->create([
            'title' => ucfirst($model::MODULE_NAME),
            'description' => 'List of all '.Str::plural($model::MODULE_NAME),
            'keywords' => 'page,'.str_replace('-', ',', Str::kebab($model::MODULE_NAME)),
        ]);
        return $page;
    }

    /**
     * @param  \HalcyonLaravel\Base\Models\Contracts\BaseModelPermissionInterface  $modelClass
     * @param  bool  $isAddToAdminRole
     * @param  array  $except
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
     * @param  bool  $isAddToAdminRole
     * @param  array  $except
     */
    private function permissionStore($modelClassOrArray, bool $isAddToAdminRole = true, array $except = [])
    {
        $permissionNames = is_array($modelClassOrArray) ? $modelClassOrArray : $modelClassOrArray::permissions();

        $roleModel = app(config('permission.models.role'));

        $config = config('access.users');
        foreach ($permissionNames as $permissionName) {
            $permission = app(config('permission.models.permission'))::create([
                'name' => $permissionName,
            ]);
//            https://github.com/spatie/laravel-permission/wiki/Global-%22Admin%22-role
//            $roleModel::findByName($config['system_role'])->givePermissionTo($permission);
            if ($isAddToAdminRole) {
                if (!in_array($permissionName, $except)) {
                    $roleModel::findByName($config['admin_role'])->givePermissionTo($permission);
                }
            }
        }
    }

    /**
     * @param  array  $permissionName
     * @param  bool  $isAddToAdminRole
     * @param  array  $except
     */
    public function seederPermissionArray(array $permissionName, bool $isAddToAdminRole = true, array $except = [])
    {
        $this->permissionStore($permissionName, $isAddToAdminRole, $except);
    }

    /**
     * @param  \Spatie\MediaLibrary\HasMedia\HasMedia  $model
     * @param $file
     * @param  array|null  $customProperties
     * @param  string  $collectionName
     * @param  string|null  $defaultPath
     */
    public function seederUploader(
        HasMedia $model,
        $file,
        array $customProperties = null,
        $collectionName = 'images',
        string $defaultPath = null
    ) {
        if (app()->environment() == 'testing') {
            return;
        }
        print_r(get_class($model)." Seeding $file ... \n");
        if ($model instanceof BaseModelInterface) {
            $fileName = $model->base();
        } else {
            if (filter_var($file, FILTER_VALIDATE_URL) OR is_string($file)) {
                $fileName = explode('/', $file);
                $fileName = $fileName[count($fileName) - 1];

            } else {
                $fileName = $file->getClientOriginalName();
            }
//            $fileName = explode('.', $fileName)[0];
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

        if (filter_var($file, FILTER_VALIDATE_URL)) {
            $media = $model
                ->addMediaFromUrl($file);
//                ->usingFileName($fileName.'.'.pathinfo($file, PATHINFO_EXTENSION));
//                ->usingFileName($fileName);
        } elseif (is_string($file)) {
            $media = $model
                ->copyMedia(is_null($defaultPath) ? test_file_path($file) : ($defaultPath.DIRECTORY_SEPARATOR.$file));
//                ->usingFileName($fileName.'.'.pathinfo($file, PATHINFO_EXTENSION));
//                ->usingFileName($fileName);

        } elseif (get_class($file) instanceof UploadedFile) {

            $media = $model
                ->addMedia($file)
                ->preservingOriginal();
//                ->usingFileName($fileName.'.'.$file->clientExtension());
//                ->usingFileName($fileName);
        }

        $media
            ->withCustomProperties($customProperties)
            ->toMediaCollection($collectionName);

        print_r(get_class($model)." Seeding done!\n");
    }

}