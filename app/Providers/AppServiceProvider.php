<?php

namespace App\Providers;

use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Intervention\Image\Image;
use Optix\Media\Facades\Conversion;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        \App\RoleMenu::observe(\App\Observers\RoleMenuObserver::class);
        \App\Menu::observe(\App\Observers\MenuObserver::class);
        \App\User::observe(\App\Observers\UserObserver::class);
        \App\Organization::observe(\App\Observers\OrganizationObserver::class);

        Conversion::register('thumbnail', function (Image $image) {
            return $image->resize(300, null, function ($constraint) {
                $constraint->aspectRatio();
            });
        });
        Relation::morphMap([
            'khu-bao-ton' => \App\ProtectedArea::class,
            'co-so-bao-ton' => \App\CoSoBaoTon::class,
            'hanh-lang-da-dang-sinh-hoc' => \App\HanhLangDaDang::class,
            'da-dang-sinh-hoc-cao' => \App\KhuDaDangSinhHocCao::class,
            'vung-ngap-nuoc-quan-trong' => \App\DatNgapNuoc::class,
            'canh-quan-sinh-thai' => \App\KhuCanhQuanSinhThai::class,
            'khu-du-tru-sinh-quyen' => \App\KhuCanhQuanSinhThai::class,
            'khu-du-tru-sinh-quyen' => \App\KhuDuTruSinhQuyen::class,
            'khu_bao_ton' => \App\ProtectedArea::class,
            'co_so_bao_ton' => \App\CoSoBaoTon::class,
            'vung_chim' => \App\VungChimQuanTrong::class,
            'hanh_lang' => \App\HanhLangDaDang::class,
            'khu_da_dang_sinh_hoc' => \App\KhuDaDangSinhHocCao::class,
            'vung_dat' => \App\DatNgapNuoc::class,
            'khu_canh_quan' => \App\KhuCanhQuanSinhThai::class,
            'sinh_quyen' => \App\KhuDuTruSinhQuyen::class,
            'he_sinh_thai' => \App\Lookup::class,
        ]);
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
