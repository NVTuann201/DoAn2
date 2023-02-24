<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Role;
use App\RoleMenu;
use App\Traits\GetDataCache;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Exception;

class RoleController extends Controller
{
    use GetDataCache;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function __construct()
    {
        $this->middleware(['auth']);
    }

    public function index()
    {
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.system.role.add', [
            'menus' => $this->getMenusForUser(Auth::user()),
        ]);
    }

    public function add(Request $request)
    {
        $info = $request->all();
        $validator = Validator::make($info, [
            'name' => 'required|max:255',
            'code' => 'required|max:255',
            'color' => 'required',
            'system' => 'boolean',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => __('message.fails'),
            ], 500, []);
        }

        $info['inactive'] = false;
        try {
            Role::query()
                ->create($info);
            $user = Auth::user();
            createLog($user->id, 'add_role', $request->ip(), $request->header('User-Agent'), 'Thêm mới quyền '.$info['name']);
            return response()->json([
                'message' => __('message.success'),
            ], 200, []);
        } catch (\Exception $exception) {
            DB::rollBack();
            return response()->json([
                'message' => __('message.fails'),
            ], 500, []);
        }
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $query = Role::query();
        $query->where('id', $id);
        $role = $query->get();
        return view('admin.system.role.update', [
            'role' => $role,
            'menus' => $this->getMenusForUser(Auth::user()),
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $user = Auth::user();
        $role = Role::query()->findOrFail($id);
        $info = $request->only(['code', 'name', 'description', 'system', 'color']);

        $validator = Validator::make($info, [
            'code' => [
                Rule::unique('roles')->ignore($role->id),
                'max:255',
                'required'
            ],
            'name' => 'required|max:255',
            'system' => 'boolean',
            'color' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => __('message.fails'),
                'result' => $validator->errors(),
            ], 400, []);
        }

        try {
            $role->update($info);
            $user = Auth::user();
            createLog($user->id, 'update_role', $request->ip(), $request->header('User-Agent'), 'Cập nhật quyền '.$role['name']);
            return response()->json([
                'message' => __('message.success'),
                'result' => [],
            ], 200, []);
        } catch (Exception $exception) {
            DB::rollBack();
            Log::error($exception);
            return response()->json([
                'message' => __('message.fails'),
                'result' => [],
            ], 500, []);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function delete(Request $request, $id)
    {
        $role = Role::findOrFail($id);

        if ($role['code'] == 'sysadmin') {
            return response()->json([
                'message' => __('message.fails'),
            ], 500, []);
        } else {
            DB::beginTransaction();
            try {
                $role->delete();
                $user = Auth::user();
                createLog($user->id, 'delete_role', $request->ip(), $request->header('User-Agent'), 'Xóa quyền '.$role['name']);
                DB::commit();
                return response()->json([
                    'message' => __('message.success'),
                ], 200, []);
            } catch (\Exception $ex) {
                Log::error($ex);
                DB::rollback();
                return response()->json([
                    'message' => __('message.fails'),
                ], 500, []);
            }
        }
    }

    public function addRoleMenu(Request $request, $id)
    {
        $info = $request->all();

        DB::beginTransaction();
        try {
            $checkRole = RoleMenu::where('menu_id', $id)->where('role_id', isset($info['role_id']) ? $info['role_id'] : null)->first();
            $checkMenu = RoleMenu::where('role_id', $id)->where('menu_id', isset($info['menu_id']) ? $info['menu_id'] : null)->first();
            if (!empty($checkRole) || !empty($checkMenu)) {
                return response()->json([
                    'message' => __('message.fails'),
                ], 200, []);
            }
            if (isset($info['role_id'])) {
                RoleMenu::query()->create([
                    'role_id' => $info['role_id'],
                    'menu_id' => $id
                ]);
            }
            if (isset($info['menu_id'])) {
                RoleMenu::query()->create([
                    'role_id' => $id,
                    'menu_id' => $info['menu_id']
                ]);
            }
            DB::commit();
            return response()->json([
                'message' => __('message.success'),
            ], 200, []);
        } catch (\Exception $ex) {
            Log::error($ex);
            DB::rollback();
            return response()->json([
                'message' => __('message.fails'),
            ], 500, []);
        }
    }

    public function deleteRoleMenu($id)
    {
        $rolemenu = RoleMenu::findOrFail($id);

        DB::beginTransaction();
        try {
            $rolemenu->delete();
            DB::commit();
            return response()->json([
                'message' => __('message.success'),
            ], 200, []);
        } catch (\Exception $ex) {
            Log::error($ex);
            DB::rollback();
            return response()->json([
                'message' => __('message.fails'),
            ], 200, []);
        }
    }
}
