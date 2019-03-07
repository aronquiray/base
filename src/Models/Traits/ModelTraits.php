<?php

namespace HalcyonLaravel\Base\Models\Traits;

use Illuminate\Support\Arr;
use Route;

trait ModelTraits
{
    /**
     * Returns the list of permission for this group.
     *
     * @param null $keys
     *
     * @return mixed
     */
    public static function permission($keys = null)
    {
        $permissions = static::permissions();
        if (!is_null($keys) && is_array($keys)) {
            foreach ($permissions as $p => $permission) {
                if (!in_array($p, $keys)) {
                    Arr::forget($permissions, $p);
                }
            }

            return $permissions;
        }

        return $permissions[$keys];
    }

    /**
     * Returns the value of a given key in the baseable function
     *
     * @param string|null $key
     *
     * @return string
     */
    public function base(string $key = null): string
    {
        $config = $this->baseable();
        if (array_key_exists($key, $config) && !is_null($key)) {
            $key = $config[$key];
        } else {
            $key = $config['source'];
        }

        return $this->$key;
    }

    /**
     * Returns the list of links within the selected group
     *
     * @param string $group
     * @param null   $keys
     * @param bool   $onlyLinks
     *
     * @return array|mixed
     */
    public function actions(string $group, $keys = null, bool $onlyLinks = false)
    {
        abort_if(!in_array($group, ['backend', 'frontend']), 500, 'Invalid action group.');

        $user = auth()->user();

        if (method_exists($this, 'additionalLinks')) {
            $links = array_merge_recursive($this->links(), $this->additionalLinks())[$group];
        } else {
            $links = $this->links()[$group];
        }

        foreach ($links as $type => $link) {
            if (
                (array_key_exists('permission', $link) && $user && !$user->hasPermissionTo($link['permission'])) ||
                (!is_null($keys) && is_array($keys) && !in_array($type, $keys)) ||
                (!is_null($keys) && !is_array($keys) && $keys != $type)) {
                Arr::forget($links, $type);
            }
        }

        $validAttributeKeys = [
            'type',
            'permission',
            'label',
            'url',
            'group',
            'redirect',
            'icon',
            'class',

        ];
        $currentClass = get_class($this);
        foreach ($links as $link) {
            foreach ($link as $keyAttribute => $v) {
                abort_if(!in_array($keyAttribute, $validAttributeKeys), 500,
                    "Invalid attribute key [$keyAttribute] on {$currentClass}::links().");
            }
        }

        // skip not existed route
        $filter = [];
        foreach ($links as $type => $link) {
            $url = $this->generateUrl($link['url']);
            if (!is_null($url)) {
                $link['url'] = $url;
                $filter[$type] = $link;
            }
            if (isset($link['redirect'])) {
                $url = $this->generateUrl($link['redirect']);
                if (!is_null($url)) {
                    $link = $filter[$type];
                    $link['redirect'] = $url;
                    $filter[$type] = $link;
                }
            }
        }
        $links = $filter;

        if ($onlyLinks == true) {
            $filter = [];
            foreach ($links as $type => $link) {
                $filter[$type] = $link['url'];
            }
            $links = $filter;
        }
        if (!is_null($keys) && is_string($keys) && count($links) == 1) {
            return $links[$keys];
        }

        return $links;
    }

    /**
     * @param $urlOrRoute
     *
     * @return string|null
     */
    private function generateUrl($urlOrRoute)
    {
        if (filter_var($urlOrRoute, FILTER_VALIDATE_URL)) {
            return $urlOrRoute;
        }

        $param = isset($urlOrRoute[1]) ? $urlOrRoute[1] : [];
//        dd(__METHOD__,$urlOrRoute[0],$param);
        if (Route::has($urlOrRoute[0])) {
            return route($urlOrRoute[0], $param);
        }
        return null;
    }
}