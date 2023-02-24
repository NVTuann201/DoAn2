<?php

namespace App\Http\Controllers\Admin;

use App\DarwinCoreTaxon;
use App\ProtectedArea;
use App\DatasetResource;
use Session;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\RoleMenu;
use App\Traits\GetDataCache;


class HomeController extends Controller
{
    use  GetDataCache;
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function index()
    {
        $user = Auth::user();
        $this->flush();
        $menuHome = RoleMenu::query()
            ->where('home_router', true)
            ->where('role_id', $user->role_id)
            ->first();
        if (isset($menuHome) && isset($menuHome->menu->router_link)) {
            return redirect()->route($menuHome->menu->router_link);
        }
        if (in_array($user->role->code, ['sysadmin', 'admin'])) {
            return redirect()->route('admin/protectedareas');
        }
        if (in_array($user->role->code, ['ND2019'])) {
            return redirect()->route('user.profile');
        }
        return redirect()->route('profile');
    }
}
