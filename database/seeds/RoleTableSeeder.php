<?php

use Illuminate\Database\Seeder;
use App\Role;
use App\User;
use App\Menu;
use App\RoleMenu;

class RoleTableSeeder extends Seeder
{
    public function run()
    {
        DB::statement('TRUNCATE role_menus, roles, menus RESTART IDENTITY CASCADE;');                

        $sysadmin = Role::create(array(                
            'name' => 'sysadmin',
            'description'    => 'Manager system',
            'code' => 'sysadmin',
            'color' => 'danger'           
        ));   
        
        User::create(array(
            'name'     => 'System admin',
            'username' => 'sysadmin',
            'email'    => 'sysadmin@hp.com',
            'password' => Hash::make('12345678'),            
            'role_id' => $sysadmin->id,
            'created' => '2019-2-27',
            'modified' => '2019-2-28',
            'inactive' => false
        ));

        $menuHeThong = Menu::create(array(
            'name' => 'System',
            'parent_id' => null,
            'router_link' => null,
            'fa_icon' => 'mdi mdi-settings fa-fw',
            'inactive' => false,
            'group' => 'System',
            'order' => 2
        ));

        RoleMenu::create(array(
            'role_id' => $sysadmin->id,
            'menu_id' => $menuHeThong->id,
            'home_router' => false
        )); 

        $menuRole = Menu::create(array(
            'name' => 'Role',
            'parent_id' => $menuHeThong->id,
            'router_link' => 'system.roles',
            'fa_icon' => 'icon-people fa-fw',
            'inactive' => false,
            'group' => 'System',
            'order' => 1
        ));  
        
        RoleMenu::create(array(
            'role_id' => $sysadmin->id,
            'menu_id' => $menuRole->id,
            'home_router' => false
        ));
        
        $menuNguoiDung = Menu::create(array(
            'name' => 'User',
            'parent_id' => $menuHeThong->id,
            'router_link' => 'users',
            'fa_icon' => 'icon-user fa-fw',
            'inactive' => false,
            'group' => 'System',
            'order' => 2
        ));  
        
        RoleMenu::create(array(
            'role_id' => $sysadmin->id,
            'menu_id' => $menuNguoiDung->id,
            'home_router' => true
        ));

        $menuBangPhanQuyen = Menu::create(array(
            'name' => 'Function',
            'parent_id' => $menuHeThong->id,
            'router_link' => 'system.functions',
            'fa_icon' => 'icon-layers fa-fw',
            'inactive' => false,
            'group' => 'System',
            'order' => 3
        ));

        RoleMenu::create(array(
            'role_id' => $sysadmin->id,
            'menu_id' => $menuBangPhanQuyen->id,
            'home_router' => false
        ));

        $menuChucNang = Menu::create(array(
            'name' => 'Menu',
            'parent_id' => $menuHeThong->id,
            'router_link' => 'system.menus',
            'fa_icon' => 'icon-menu fa-fw',
            'inactive' => false,
            'group' => 'System',
            'order' => 4
        ));

        RoleMenu::create(array(
            'role_id' => $sysadmin->id,
            'menu_id' => $menuChucNang->id,
            'home_router' => false
        ));        
    }
}