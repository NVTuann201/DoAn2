<?php

namespace App\Providers;

use App\Menu;
use App\Traits\GetDataCache;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Request;

class BreadcrumbServiceProvider extends ServiceProvider
{
    use GetDataCache;
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        // expression nhan array gom parent va children
        // parent la route_link trong bang menu
        // children de sinh ra phan breadcrum phia sau
        Blade::directive('breadcrumb', function ($expression) {
            // $menu = $this->getMenusForUser(Auth::user());
            $user = Auth::user();
            $params = [];
            $breadcrumbs=[];
            if (!empty($expression))
                eval("\$params = $expression;");
            if (isset($params['parent'])) {
                $route_name = $params['parent'];
            } else {
                $route_name =  Request::route()->getName();
            }
            $parents = $this->getBreadCrumbsForUser(Auth::user(), $route_name);
            if (!empty($parents)) {
                foreach ($parents as $i => $parent) {
                    $link = Route::has($parent['router_link']) ? route($parent['router_link']) : '#';
                    $class = '';
                    if (($i == count($parents) - 1)  && empty($params['children'])) {
                        $class = 'active';
                    }
                    $breadcrumbs[] = "<li class='breadcrumb-item'><a href='{$link}' class='{$class}'>{$parent['name']}</a></li>";
                }
            }
            if (isset($params['children'])) {
                foreach ($params['children'] as $i => $child) {
                    $link = $child['link'];
                    $class = $i == (count($params['children']) - 1) ?  'active' : '';
                    $breadcrumbs[] = "<li class='breadcrumb-item'><a href='{$link}' class='{$class}'>{$child['name']}</a></li>";
                }
            }
            return join("", $breadcrumbs);
        });

        Blade::directive('endBreadcrumb', function ($expression) {
            return "";
        });
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
