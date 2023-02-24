<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Validator;
use App\Menu;
use App\User;
use App\Role;
use App\RoleMenu;
use App\Customer;
use App\Traits\ExecuteString;
use App\Traits\GetDataCache;
use DB;
use App\Http\Controllers\Controller;
use Carbon;

class MenuController extends Controller
{
    use ExecuteString, GetDataCache;

    public function __construct()
    {
        $this->middleware(['auth']);
    }

    public function indexMenu()
    {
        return view('admin.system.menu.index');
    }

    public function getMenu(Request $request)
    {
        $user = Auth::user();
        $perPage = $request->get('perpage', 10);
        $page = $request->get('page', 1);
        $search = $request->get('search');

        $query = Menu::query();

        if (isset($search)) {
            $query->where(function ($query) use ($search) {
                $query->orWhere('name', 'ilike', "%{$search}%");
                $query->orWhere('router_link', 'ilike', "%{$search}%");
                $query->orWhere('fa_icon', 'ilike', "%{$search}%");
            });
        }

        $query->with('parent');

        $query->orderBy('parent_id', 'desc');

        $data = $query->paginate($perPage, ['*'], 'page', $page);

        return response()->json([
            'menus' => $this->getMenusForUser($user),
            'data' => $data,
            'search' => $search,
            'perPage' => $perPage,
            'menu_parents' => $this->getDataByName(Menu::class),
        ], 200, []);
    }


    public function addMenu(Request $request)
    {
        $info = $request->all();
        $user = Auth::user();
        $validator = Validator::make($info, [
            'name' => 'required|max:255',
            'router_link' => 'required|max:255',
            'fa_icon' => 'required',
            'order' => 'required|numeric|min:1'
        ]);
        if ($validator->fails()) {
            return response()->json([
                'message' => __('message.vaidator'),
            ], 200, []);
        }

        try {
            Menu::query()
                ->create($info);
            $user = Auth::user();
            createLog($user->id, 'add_menu', $request->ip(), $request->header('User-Agent'), 'Thêm mới Menu ' . $info['name']);
            return response()->json([
                'message' => __('message.success'),
            ], 200, []);
        } catch (Exception $exception) {
            return response()->json([
                'message' => __('message.fails'),
            ], 200, []);
        }
    }

    public function updateMenu(Request $request, $id)
    {
        $menu = Menu::query()->findOrFail($id);

        $info = $request->only(['parent_id', 'name', 'router_link', 'fa_icon', 'order', 'inactive']);
        $validator = Validator::make($info, [
            'name' => 'required|max:255',
            'router_link' => 'required|max:255',
            'fa_icon' => 'required',
            'order' => 'required|numeric|min:1'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => __('message.vaidator'),
            ], 400, []);
        }
        DB::beginTransaction();
        try {
            $menu->update($info);
            $user = Auth::user();
            createLog($user->id, 'update_menu', $request->ip(), $request->header('User-Agent'), 'Cập nhật Menu ' . $info['name']);
            DB::commit();
            return response()->json([
                'message' => __('message.success'),
            ], 200, []);
        } catch (Exception $exception) {
            DB::rollback();
            return response()->json([
                'message' => __('message.fails'),
            ], 500, []);
        }
    }

    public function deleteMenu(Request $request, $id)
    {
        $menu = Menu::findOrFail($id);
        if ($menu->id == 1) {
            return response()->json([
                'message' => __('message.fails'),
            ], 500, []);
        } else {
            try {
                DB::beginTransaction();
                RoleMenu::query()
                    ->where('menu_id', $id)
                    ->delete();
                $info = Menu::where('menu_id', $id)->first();
                Menu::destroy($id);
                $user = Auth::user();
                createLog($user->id, 'delete_menu', $request->ip(), $request->header('User-Agent'), 'Xóa menu ' . $info['ten']);
                DB::commit();
                return response()->json([
                    'message' => __('message.success'),
                ], 200, []);
            } catch (Exception $exception) {
                DB::rollBack();
                dd($exception);
                return response()->json([
                    'message' => __('message.fails'),
                ], 500, []);
            }
        }
    }

    public function show($id)
    {
        $query = Menu::query();
        $query->where('id', $id);
        $menu = $query->get();
        return view('admin.system.menu.update', [
            'menu' => $menu,
            'menus' => $this->getMenusForUser(Auth::user()),
        ]);
    }

    public function showAddMenu()
    {
        return view('admin.system.menu.add');
    }
}
