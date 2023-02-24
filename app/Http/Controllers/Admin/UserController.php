<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Validator;
use App\Menu;
use App\User;
use App\Role;
use App\Customer;
use App\Traits\GetDataCache;
use DB;
use App\Http\Controllers\Controller;
use App\Province;
use Carbon;
use Exception;


class UserController extends Controller
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

    public function index(Request $request)
    {
        if (!$request->ajax()) {
            return view('admin.system.user.index', ['user' => Auth::user()]);
        }
        $perPage = $request->get('perpage', 10);
        $page = $request->get('page', 1);
        $search = $request->get('search');
        $search_role = $request->get('search_role');
        $search_status = $request->get('search_status');
        $search_organization = $request->get('search_organization');
        $query = User::query();
        if (isset($request->with) && is_array($request->with)) {
            $query->with($request->with);
        }

        $roles = $this->getDataByName(Role::class)->sortBy('name');

        if (isset($search)) {
            $query->where(function ($query) use ($search) {
                $query->orWhere('name', 'ilike', "%{$search}%");
                $query->orWhere('username', 'ilike', "%{$search}%");
                $query->orWhere('email', 'ilike', "%{$search}%");
            });
        }
        if (isset($search_role)) {
            $query->where('role_id', $search_role);
        }
        if (isset($search_status)) {
            $query->where('active', $search_status);
        }
        if (isset($search_organization)) {
            $query->where('organization_id', $search_organization);
        }
        $query->orderBy('isApprove');
        $query->orderBy('updated_at');
        $query->orderBy('name');
        $query->with('mediable.media');
        $users = $query->paginate($perPage, ['*'], 'page', $page);

        return response()->json([
            'message' => __('message.success'),
            'result' => $users
        ], 200, []);
    }

    public function add(Request $request)
    {
        $user = Auth::user();
        $info = $request->all();
        $validator = Validator::make($info, [
            'name' => 'required|max:255',
            'email' => 'required|email|max:255|unique:users,email',
            'password' => 'required|max:255|min:6',
            'role_id' => 'required',
        ], [
            'email.unique'=>'Email đã tồn tại', 
        ]);
        if ($validator->fails()) {
            return response()->json([
                'message' => __('message.vaidator'),
                'error' => $validator->errors()
            ], 200, []);
        }

        $info['username'] = $info['email'];
        if (isset($info['password'])) {
            $info['password'] = Hash::make($info['password']);
        }
        if (empty($info['inactive'])) {
            $info['inactive'] = false;
        }
        if (empty($info['created_at'])) {
            $info['created_at'] = Carbon\Carbon::now();;
        }
        if (empty($info['email_token'])) {
            $info['email_token'] = str_random(20);
        }
        try {
            $model = User::query()
                ->create($info);
            createLog($user->id, 'add_user', $request->ip(), $request->header('User-Agent'), 'Thêm mới người dùng '.$user['name']);
            if (!empty($info['province_ids'])) {
                $model->provinces()->sync($info['province_ids']);
            }
            return response()->json([
                'message' => __('message.success'),
            ], 200, []);
        } catch (\Exception $exception) {
            return response()->json([
                'message' => __('message.fails'),
            ], 200, []);
        }
    }

    public function create()
    {
        $provinces = Province::select('id', 'name_vietnamese as name')->get();
        $roles = \App\Role::get();
        return view('admin.system.user.add', [
            'danhmucs' => [
                'provinces' => $provinces,
                'roles' => $roles,
            ]
        ]);
    }

    public function show($id)
    {
        $query = User::query()->with(['provinces' => function ($q) {
            $q->select('provinces.id', 'provinces.name_vietnamese as name');
        }, 'coso']);
        $user = $query->findOrFail($id);
        $provinces = Province::select('id', 'name_vietnamese as name')->get();
        $roles = \App\Role::get();
        return view('admin.system.user.update', [
            'user' => $user,
            'danhmucs' => [
                'provinces' => $provinces,
                'roles' => $roles,
            ]
        ]);
    }
    public function approve($id)
    {
        $user = User::query()->findOrFail($id);
        try {
            $user->isApprove = true;
            $user->save();
            return response()->json([
                'message' => 'message.success',
                'result' => [],
            ], 200, []);
        } catch (Exception $exception) {
            Log::error($exception);
            return response()->json([
                'message' => __('system.update_error'),
                'result' => [],
            ], 500, []);
        }
    }
    public function update(Request $request, $id)
    {
        $info = $request->all();
        $user = User::query()->findOrFail($id);
        $validator = Validator::make($info, [
            'email' => 'required|email|max:255|unique:users,email,' . $id,
            'role_id' => 'required'
        ], [
            'email.unique'=>'Email đã tồn tại', 
        ]);
        if ($validator->fails()) {
            return response()->json([
                'message' => __('message.vaidator'),
                'error' => $validator->errors()
            ], 200, []);
        }

        $info['username'] = $info['email'];
        if (isset($info['password'])) {
            $info['password'] = Hash::make($info['password']);
        } else {
            unset($info['password']);
        }

        if (empty($info['inactive'])) {
            $info['inactive'] = false;
        }
        if (empty($info['updated_at'])) {
            $info['updated_at'] = Carbon\Carbon::now();
        }

        DB::beginTransaction();
        try {
            $user->update($info);
            createLog($user->id, 'update_user', $request->ip(), $request->header('User-Agent'), 'Cập nhật người dùng '.$user['name']);
            if (isset($info['province_ids'])) {
                $user->provinces()->sync($info['province_ids']);
            }
            DB::commit();
            return response()->json([
                'message' => 'message.success',
                'result' => [],
            ], 200, []);
        } catch (Exception $exception) {
            DB::rollBack();
            Log::error($exception);
            return response()->json([
                'message' => __('system.update_error'),
                'result' => [],
            ], 500, []);
        }
    }

    public function deleteUser(Request $request, $id)
    {
        $user = Auth::user();
        $customer = User::find($id);
        if ($user->id == $customer->id) {
            return response()->json([
                'message' => __('message.fails'),
            ], 200, []);
        }
        if ($customer->role_id == 1 && $user->role_id != 1) {
            return response()->json([
                'message' => __('message.fails'),
            ], 200, []);
        }

        DB::beginTransaction();
        try {
            $customer->delete();
            createLog($user->id, 'delete_user', $request->ip(), $request->header('User-Agent'), 'Xóa người dùng '.$user['name']);
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


    public function profile()
    {
        $user = Auth::user();
        return view('user.profile', [
            'lang' => \Session::get('locale'),
            'user' => $user
        ]);
    }

    public function updateProfile(Request $request, $id)
    {
        $info = $request->all();
        $user = User::query()->findOrFail($id);
        $validator = Validator::make($info, [
            'name' => 'required'
        ]);
        if ($validator->fails()) {
            return response()->json([
                'message' => __('message.vaidator'),
            ], 200, []);
        }

        try {
            $user->update($info);
            return response()->json([
                'message' => 'message.success',
                'result' => [],
            ], 200, []);
        } catch (Exception $exception) {
            Log::error($exception);
            return response()->json([
                'message' => __('system.update_error'),
                'result' => [],
            ], 500, []);
        }
    }
}
