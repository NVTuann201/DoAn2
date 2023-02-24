<?php

namespace App\Traits;

use App\Menu;

trait GetMenus
{
    public function getMenusForUser($user)
    {
        $data = collect([
            'tree' => [],
            'mega' => [],
        ]);
        if ($user && !empty($user->role_id)) {
            if ($this->has('menu', $user->role_id)) {
                $data = $this->get('menu', $user->role_id);
            } else {
                $data->tree = Menu::query()
                    ->ofRole($user->role_id)
                    ->with(['children' => function ($query) use ($user) {
                        $query->ofRole($user->role_id)
                            ->orderBy('order');
                    }, 'roles'])
                    ->firstLevel()
                    ->orderBy('order')
                    ->get();
                $data->mega = Menu::query()
                    ->ofRole($user->role_id)
                    ->select('id', 'group', 'name', 'router_link', 'order')
                    ->get()
                    ->groupBy('group');
                $this->put('menu', $user->role_id, $data);
            }
        }

        return $data;
    }

    public function forgetMenuForRole($roleId)
    {
        $this->forget('menu', $roleId);
    }

    public function forgetMenuAll()
    {
        $roles = $this->getDataByName(\App\Role::class);
        foreach ($roles as $role) {
            $this->forget('menu', $role->id);
        }
        if ($this->has('breadcrumb-menu',  $role->id)) {
            $keys = $this->get('breadcrumb-menu',  $role->id);
            foreach ($keys as $key) {
                $this->forget($key);
            }
        }
    }
    public function getBreadCrumbsForUser($user, $route_name)
    {
        $data = [];
        if ($user && $user->role_id) {
            $key = 'breadcrumb-menu' . $route_name . $user->role_id;
            if ($this->has($key, '')) {
                $data = $this->get($key, '');
            } else {
                $menu = Menu::where('router_link', $route_name)
                    ->ofRole($user->role_id)->first();
                if (!empty($menu)) {
                    $parents = collect([]);
                    $parent = $menu->parent;
                    while (!is_null($parent)) {
                        $parents->prepend($parent);
                        $parent = $parent->parent;
                    }
                    $parents->push($menu);
                    $data = $parents->values()->toArray();
                    $this->put($key, '', $data);
                }
                $keys = [];
                if ($this->has('breadcrumb-menu',  $user->role_id)) {
                    $keys = $this->get('breadcrumb-menu',  $user->role_id);
                }
                $keys[] = $key;
                $this->put('breadcrumb-menu',  $user->role_id, $keys);
            }
        }
        return $data;
    }
}
