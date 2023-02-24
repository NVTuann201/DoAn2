<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\KhuDaDangSinhHocCao;
use App\Lookup;
use App\ProtectedArea;
use App\Province;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class KhuDaDangSinhHocCaoController extends Controller
{
    public function viewIndex()
    {
        return view('admin.resources.dadangsinhoccao.index');
    }
    public function showFormAdd()
    {
        $tinhTrang = Lookup::where('group', 'tinhtranghanhlang')->get();
        $tinhThanh = Province::get();
        $danhmucs = [
            'tinh_trang' => $tinhTrang,
            'tinh_thanh' => $tinhThanh
        ];
        return view('admin.resources.dadangsinhoccao.add', ['danhmucs' => json_encode($danhmucs)]);
    }

    public function add(Request $request)
    {
        $info = $request->only(
            'ten',
            'mo_ta',
            'dien_tich',
            'tinh_trang_id',
            'can_cu_de_xuat',
            'quyet_dinh_thanh_lap',
            'ngay_thanh_lap',
            'co_quan_ban_hanh',
            'co_quan_quan_ly',
            'quoc_te_cong_nhan',
            'danh_hieu_quoc_te',
            'muc_do',
            'de_xuat_quan_ly',
            'tinh_thanh_id',
            'ghi_chu'
        );
        $validator = Validator::make($info, [
            'ten' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'message' => __('Lỗi dữ liệu'),
            ], 401, []);
        }
        try {
            $info['tinh_thanh_id'] =  $info['tinh_thanh_id'] ? json_encode($info['tinh_thanh_id']) : null;
            DB::beginTransaction();
            KhuDaDangSinhHocCao::create($info);
            $user = Auth::user();
            createLog($user->id, 'add_khu_da_dang_sinh_hoc', $request->ip(), $request->header('User-Agent'), 'Thêm mới khu đa dạng sinh học '.$info['ten']);
            DB::commit();
            return response(['message' => 'Done'], 200);
        } catch (\Exception $e) {
            dd($e);
            return response(['message' => 'Không thể thêm mới'], 501);
        }
    }
    public function update(Request $request)
    {
        $info = $request->only(
            'id',
            'ten',
            'mo_ta',
            'dien_tich',
            'tinh_trang_id',
            'can_cu_de_xuat',
            'quyet_dinh_thanh_lap',
            'ngay_thanh_lap',
            'co_quan_ban_hanh',
            'co_quan_quan_ly',
            'quoc_te_cong_nhan',
            'danh_hieu_quoc_te',
            'muc_do',
            'de_xuat_quan_ly',
            'tinh_thanh_id',
            'ghi_chu'
        );
        $validator = Validator::make($info, [
            'ten' => 'required',
            'id' => 'required'
        ]);
        if ($validator->fails()) {
            return response()->json([
                'message' => __('Lỗi dữ liệu'),
            ], 401, []);
        }
        try {
            $info['tinh_thanh_id'] = $info['tinh_thanh_id'] ? json_encode($info['tinh_thanh_id']) : null;
            DB::beginTransaction();
            KhuDaDangSinhHocCao::find($info['id'])->update($info);
            $user = Auth::user();
            createLog($user->id, 'update_khu_da_dang_sinh_hoc', $request->ip(), $request->header('User-Agent'), 'Cập nhật khu đa dạng sinh học '.$info['ten']);
            DB::commit();
            return response(['message' => 'Done'], 200);
        } catch (\Exception $e) {
            dd($e);
            return response(['message' => 'Không thể thêm mới'], 501);
        }
    }

    public function getKhuVuc(Request $request){
        $page = $request->get('page', 1);
        $per_pager = $request->get('perPage', 5);
        $search = $request->get('search', null);
        $tinh_trang_id = $request->get('tinh_trang_id', null);
        $query = KhuDaDangSinhHocCao::query();
        if ($search != null) {
            $search = trim($search);
            $query = $query->where(function ($query) use ($search) {
                $query->where('ten', 'ilike', "%{$search}%")
                    ->orWhere('ghi_chu', 'ilike', "%{$search}%")
                    ->orWhere('can_cu_de_xuat', 'ilike', "%{$search}%")
                    ->orWhere('quyen_dinh_thanh_lap', 'ilike', "%{$search}%")
                    ->orWhere('co_quan_ban_hanh', 'ilike', "%{$search}%")
                    ->orWhere('de_xuat_quan_ly', 'ilike', "%{$search}%")
                    ->orWhere('danh_hieu-quoc_te', 'ilike', "%{$search}%")
                    ->orWhere('mo_ta', 'ilike', "%{$search}%");
            });
        }
        if($tinh_trang_id){
            $query->where('tinh_trang_id', $tinh_trang_id);
        }
        $query->orderBy('updated_at', 'DESC');
        $query->orderBy('id', 'DESC');
        $data =  $query->paginate($per_pager, ['*'], 'page', $page);
        return $data;
    }

    public function showFormEdit($id)
    {
        $tinhTrang = Lookup::where('group', 'tinhtranghanhlang')->get();
        $tinhThanh = Province::get();
        $danhmucs = [
            'tinh_trang' => $tinhTrang,
            'tinh_thanh' => $tinhThanh
        ];
        $data = KhuDaDangSinhHocCao::where('id', $id)->with(['tinhTrang'])->first();
        return view('admin.resources.dadangsinhoccao.edit', ['danhmucs' => json_encode($danhmucs), 'data' => json_encode($data)]);
    }
    public function delete(Request $request, $id){
        try {
            $info=KhuDaDangSinhHocCao::where('id',$id)->first();
            KhuDaDangSinhHocCao::find($id)->delete();
            $user = Auth::user();
            createLog($user->id, 'delete_khu_da_dang_sinh_hoc', $request->ip(), $request->header('User-Agent'), 'Xóa khu đa dạng sinh học '.$info['ten']);
            return response(['message' => 'Done'], 200);
        } catch (\Exception $e) {
            return response(['message' => 'Error'], 501);
        }
    }

    public function getChiSo(){
        $tinhTrang = Lookup::where('group', 'tinhtranghanhlang')->select('id', 'name')->get();
        foreach ($tinhTrang as $item) {
            $item['so_luong'] = KhuDaDangSinhHocCao::where('tinh_trang_id', $item['id'])->count();
        }
        return response(['tinh_trang' => $tinhTrang], 200);
    }
}
