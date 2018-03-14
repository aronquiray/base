<?php 

namespace App\Models;

// use Spatie\Permission\Traits\HasRoles;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

// use Illuminate\Database\Eloquent\SoftDeletes;


// use Cviebrock\EloquentSluggable\Sluggable;
// use Cviebrock\EloquentSluggable\SluggableScopeHelpers;


// use PackageHalcyon\Meta\Traits\Metable;

// use HalcyonLaravel\History\Traits\Historable;

// use PackageHalcyon\Base\Traits\Baseable;
// use PackageHalcyon\Base\Contracts\BaseableInterface;

class User extends Authenticatable //implements BaseableInterface
{
    // use
    // // HasRoles,
    // // Notifiable,
    // //  Sluggable,
    // //  SluggableScopeHelpers,
    // //   SoftDeletes,
    // //   Metable,
    // // Historable
    //     // Baseable
    //     ;

    protected $fillable = [
       'first_name', 'last_name', 'status',  'slug',
    ];
    /**
     * Return the baseable name for this model.
     *
     * @return String
     */
    public function baseable() :array
    {
        return [
            'source' => 'firs_name',
        ];
    }
    

    /**
     * Return the sluggable configuration array for this model.
     *
     * @return array
     */
    // public function sluggable()
    // {
    //     return [
    //         'slug' => [
    //             'source' => [
    //                 'firs_name',
    //                 'last_name',
    //             ]
    //         ]
    //     ];
    // }

    public function getRouteKeyName()
    {
        return 'slug';
    }
        

    /**
     * Return the action links for the model.
     *
     * @return array
     */
    public function actions($frontend = false)
    {
        $user = auth()->user();
        $actions = [];

        // Frontend Links
         
        if ($frontend) {
            $actions['show'] = ['type' => 'show', 'link' => route('frontend.user.show', $this)];
            return $actions;
        }
         

        // Backend Links
         
        if ($this->trashed()) {
            if ($user->can('user restore')) {
                $actions['restore'] = ['type' => 'restore',   'link' => route('admin.user.restore', $this), 'redirect' => route('admin.user.index')];
            }
            if ($user->can('user restore')) {
                $actions['purge'] = ['type' => 'purge',     'link' => route('admin.user.purge', $this), 'redirect' => route('admin.user.index')];
            }

            return $actions;
        }
        

        if ($user->can('user show')) {
            $actions['show'] = ['type' => 'show',      'link' => route('admin.user.show', $this)];
        }
        if ($user->can('user update')) {
            $actions['edit'] = ['type' => 'edit',      'link' => route('admin.user.edit', $this)];
        }
        if ($user->can('user delete')) {
            $actions['delete'] = ['type' => 'delete',    'link' => route('admin.user.destroy', $this) , 'redirect' => route('admin.user.index')];
        }

        return $actions;
    }
}
