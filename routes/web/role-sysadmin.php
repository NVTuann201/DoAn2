<?php

use Illuminate\Support\Facades\Route;

//sysadmin
Route::group(['middleware' => 'role:sysadmin'], function () {
    //Menu
    Route::get('menus', 'Admin\MenuController@indexMenu')->name('system.menus');
    Route::get('menus/info', 'Admin\MenuController@getMenu');
    Route::post('menus/add', 'Admin\MenuController@addMenu')->name('system.menus.add');
    Route::get('menus/add', 'Admin\MenuController@showAddMenu')->name('system.menus.showAddMenu');
    Route::get('menus/update/{id}', 'Admin\MenuController@show')->name('system.menus.update');
    Route::post('menus/update/{id}', 'Admin\MenuController@updateMenu');
    Route::delete('menus/delete/{id}', 'Admin\MenuController@deleteMenu')->name('system.menus.delete');

    //Role Menu
    Route::get('rolesmenu/info/{id}', 'Admin\SystemController@getRolesMenu');
    Route::post('rolesmenu/add/{id}', 'Admin\RoleController@addRoleMenu');
    Route::delete('rolesmenu/delete/{id}', 'Admin\RoleController@deleteRoleMenu');

    //Role
    Route::get('roles', 'Admin\SystemController@roles')->name('system.roles');
    Route::get('roles/add', 'Admin\RoleController@create')->name('system.roles.add');
    Route::get('roles/update/{id}', 'Admin\RoleController@show')->name('system.roles.update');
    Route::post('roles/update/{id}', 'Admin\RoleController@update');
    Route::post('roles/add', 'Admin\RoleController@add')->name('system.roles.add');
    Route::delete('roles/delete/{id}', 'Admin\RoleController@delete')->name('system.roles.delete');
});

//Sysadmin,admin
Route::group(['middleware' => 'role:sysadmin,admin'], function () {
    //User
    Route::get('users', 'Admin\UserController@index')->name('users');
    Route::get('users/add', 'Admin\UserController@create')->name('users.create');
    Route::post('users/add', 'Admin\UserController@add')->name('users.create');
    Route::get('users/update/{id}', 'Admin\UserController@show')->name('users.create');
    Route::post('users/update/{id}', 'Admin\UserController@update')->name('users.create');
    Route::delete('users/delete/{id}', 'Admin\UserController@deleteUser')->name('users.delete');
    Route::post('users/approve/{id}', 'Admin\UserController@approve');
    //UserLog
    Route::get('userlog', 'Admin\SystemController@indexUserLog')->name('userlog');
    Route::get('userlog/info', 'Admin\SystemController@getUserLog');
    Route::get('userlog/users', 'Admin\SystemController@getUsers');
    Route::get('userlog/action', 'Admin\SystemController@getAction');

    Route::get('roles/info', 'Admin\SystemController@getRoles')->middleware('cros');
    Route::get('roles/coso', 'Admin\SystemController@getRoleCoso');
});
