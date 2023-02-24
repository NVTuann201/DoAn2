<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Validator;
use App\Organization;
use App\Traits\GetDataCache;
use DB;
use App\Http\Controllers\Controller;
use Carbon;
use Exception;

class OrganizationController extends Controller
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
        return view('admin.system.organization.index');
    }

    public function getOrganization(Request $request){
        $perPage = $request->get('perpage', 10);
        $page = $request->get('page', 1);
        $search = $request->get('search');

        $query = Organization::query();

        if (isset($search)) {
            $query->where(function ($query) use ($search) {
                $query->orWhere('name', 'ilike', "%{$search}%");
                $query->orWhere('name_vietnamese', 'ilike', "%{$search}%");
                $query->orWhere('email', 'ilike', "%{$search}%");
            });
        }
        $query->orderBy('name');
        $query->with('mediable.media');
        $organization = $query->paginate($perPage, ['*'], 'page', $page);

        return response()->json([
            'message' => __('message.success'),
            'result' => $organization
        ], 200, []);
    }

    public function add(Request $request)
    {
        $info = $request->all();
        $validator = Validator::make($info, [
            'name' => 'required|max:255|unique:organizations',
            'name_vietnamese' => 'required|max:255|unique:organizations',
            'acronym' => 'required',
            'organization_type' => 'required',
            'email' => 'nullable|email'
        ],[
            'name.unique'=> 'Tên tổ chức đã tồn tại',
            'name_vietnamese.unique'=>'Tên tổ chức đã tồn tại'
        ]);
        if ($validator->fails()) {
            return response()->json([
                'message' => __('message.vaidator'),
                'data' =>  $validator->errors()
            ], 200, []);
        }
        if (empty($info['created_at'])) {
            $info['created_at'] = Carbon\Carbon::now();;
        }

        if (empty($info['updated_at'])) {
            $info['updated_at'] = Carbon\Carbon::now();;
        }

        if (empty($info['parent_organization_id'])) {
            $info['parent_organization_id'] = 0;
        }

        if (empty($info['enabled'])) {
            $info['enabled'] = 1;
        }
        try {
            Organization::query()->create($info);
            $user = Auth::user();
            createLog($user->id, 'add_to_chuc', $request->ip(), $request->header('User-Agent'), 'Thêm mới tổ chức '.$info['name']);
            
            return response()->json([
                'message' => __('message.success'),
            ], 200, []);
        } catch (Exception $exception) {
            return response()->json([
                'message' => __('message.fails'),
            ], 500, []);
        }
    }

    public function create()
    {
        return view('admin.system.organization.add', [
            'menus' => $this->getMenusForUser(Auth::user()),
        ]);
    }

    public function show($id)
    {        
        $query = Organization::query();
        $query->where('id', $id);
        $query->with('mediable.media');
        $organization = $query->get();
        $getImageUrl = $query->first()->getFirstMediaUrl();
    
        return view('admin.system.organization.update', [
            'organization' => $organization,
            'menus' => $this->getMenusForUser(Auth::user()),
            'image' => !empty($getImageUrl) ? $getImageUrl : ''
        ]);
    }

    public function update(Request $request, $id)
    {
        $organization = Organization::findOrFail($id);
        $info = $request->all();
        $validator = Validator::make($info, [
            'name' => 'required|max:255',
            'name_vietnamese' => 'required|max:255',
            'acronym' => 'required',
            'organization_type' => 'required',
            'email' => 'nullable|email'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => __('message.vaidator'),
            ], 200, []);
        }
        $trunglapname=Organization::where('id', '<>', $id)->where('name', $info['name'])->first();
        if($trunglapname){
            return response()->json([
                'message' => __('trungTen'),
            ], 200, []);
        }
        $trunglapname_vietmanese=Organization::where('id', '<>', $id)->where('name_vietnamese',$info['name_vietnamese'])->first();
        if($trunglapname_vietmanese){
            return response()->json([
                'message' => __('trungTenVNese'),
            ], 200, []);
        }
        if (empty($info['parent_organization_id'])) {
            $info['parent_organization_id'] = 0;
        }

        if (empty($info['updated_at'])) {
            $info['updated_at'] = Carbon\Carbon::now();;
        }
        DB::beginTransaction();
        try {
            $organization->update($info);   
            $user = Auth::user();
            createLog($user->id, 'update_to_chuc', $request->ip(), $request->header('User-Agent'), 'Cập nhật tổ chức '.$organization['name']);
            DB::commit();

            return response()->json([
                'message' => 'message.success',
                'result' => [],
            ], 200, []);
        } catch (Exception $exception) {
            DB::rollBack();
            \Log::error($exception);
            return response()->json([
                'message' => __('system.update_error'),
                'result' => [],
            ], 500, []);
        }
    }

    public function delete(Request $request, $id)
    {
        $organization = Organization::find($id); 
        DB::beginTransaction();
        try{
            $organization->delete();
            $user = Auth::user();
            createLog($user->id, 'delete_to_chuc', $request->ip(), $request->header('User-Agent'), 'Xóa tổ chức '.$organization['name']);
            DB::commit();
            return response()->json([
                'message' => __('message.success'),
            ], 200, []);
        }
        catch(\Exception $ex){
            \Log::error($ex);
            DB::rollback();
            return response()->json([
                'message' => __('message.fails'),
            ], 500, []);
        }
    }

}
