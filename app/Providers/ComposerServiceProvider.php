<?php

namespace App\Providers;

use App\Http\Controllers\ViewComposers\MenuComposer;
use App\Http\Controllers\ViewComposers\ProfileComposer;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class ComposerServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        View::composer(
            ['admin.layouts.header', 'admin.layouts.sidebar'],
            ProfileComposer::class
        );

        View::composer(
            ['admin.layouts.header', 'admin.layouts.sidebar'],
            MenuComposer::class
        );
    }

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
