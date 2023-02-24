<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\KhuDuTruSinhQuyen;
use App\Lookup;
use App\ProtectedArea;
use App\Province;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class KhuDuTruSinhQuyenController extends Controller
{
    public function viewIndex()
    {
        return view('admin.resources.khudutrusinhquyen.index');
    }
    public function showFormAdd()
    {
        $tinhTrang = Lookup::where('group', 'tinhtranghanhlang')->get();
        $tinhThanh = Province::get();
        $diaDiem = ProtectedArea::select('id', 'orig_name', 'desig', 'name')->get();
        $danhmucs = [
            'tinh_trang' => $tinhTrang,
            'tinh_thanh' => $tinhThanh,
            'khu_bao_ton' => $diaDiem,
        ];
        return view('admin.resources.khudutrusinhquyen.add', ['danhmucs' => json_encode($danhmucs)]);
    }

    public function showFormEdit($id)
    {
        $tinhTrang = Lookup::where('group', 'tinhtranghanhlang')->get();
        $tinhThanh = Province::get();
        $diaDiem = ProtectedArea::select('id', 'orig_name', 'desig', 'name')->get();
        $danhmucs = [
            'tinh_trang' => $tinhTrang,
            'tinh_thanh' => $tinhThanh,
            'khu_bao_ton' => $diaDiem,
        ];
        $data = KhuDuTruSinhQuyen::where('id', $id)->with(['tinhTrang', 'khuBaoTon'])->first();
        return view('admin.resources.khudutrusinhquyen.edit', ['danhmucs' => json_encode($danhmucs), 'data' => json_encode($data)]);
    }

    public function add(Request $request)
    {
        $info = $request->only(
            'ten',
            'mo_ta',
            'dien_tich',
            'tinh_trang_id',
            'dan_so',
            'quyet_dinh_thanh_lap',
            'ngay_thanh_lap',
            'co_quan_ban_hanh',
            'co_quan_quan_ly',
            'khu_bao_ton_id',
            'vung_loi',
            'dien_tich_vung_loi',
            'dan_so_vung_loi',
            'vung_dem',
            'dien_tich_vung_dem',
            'dan_so_vung_dem',
            'vung_chuyen_tiep',
            'dien_tich_vung_chuyen_tiep',
            'dan_so_vung_chuyen_tiep',
            'quoc_te_cong_nhan',
            'danh_hieu_quoc_te',
            'thoi_gian_cong_nhan',
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
            KhuDuTruSinhQuyen::create($info);
            $user = Auth::user();
            createLog($user->id, 'add_khu_du_tru_sinh_quen', $request->ip(), $request->header('User-Agent'), 'Thêm mới khu dự trữ sinh quyển '.$info['ten']);
            return response(['message' => 'Done'], 200);
        } catch (\Exception $e) {
            dd($e);
            return response(['message' => 'Không thể thêm mới'], 501);
        }
    }

    public function update(Request $request)
    {
        $info = $request->only(
            'ten',
            'id',
            'mo_ta',
            'dien_tich',
            'tinh_trang_id',
            'dan_so',
            'quyet_dinh_thanh_lap',
            'ngay_thanh_lap',
            'co_quan_ban_hanh',
            'co_quan_quan_ly',
            'khu_bao_ton_id',
            'vung_loi',
            'dien_tich_vung_loi',
            'dan_so_vung_loi',
            'vung_dem',
            'dien_tich_vung_dem',
            'dan_so_vung_dem',
            'vung_chuyen_tiep',
            'dien_tich_vung_chuyen_tiep',
            'dan_so_vung_chuyen_tiep',
            'quoc_te_cong_nhan',
            'danh_hieu_quoc_te',
            'thoi_gian_cong_nhan',
            'tinh_thanh_id',
            'ghi_chu'
        );
        $validator = Validator::make($info, [
            'ten' => 'required',
            'id' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'message' => __('Lỗi dữ liệu'),
            ], 401, []);
        }
        try {
            $info['tinh_thanh_id'] =  $info['tinh_thanh_id'] ? json_encode($info['tinh_thanh_id']) : null;
            KhuDuTruSinhQuyen::find($info['id'])->update($info);
            $user = Auth::user();
            createLog($user->id, 'update_khu_du_tru_sinh_quen', $request->ip(), $request->header('User-Agent'), 'Cập nhật khu dự trữ sinh quyển '.$info['ten']);
            return response(['message' => 'Done'], 200);
        } catch (\Exception $e) {
            dd($e);
            return response(['message' => 'Không thể thêm mới'], 501);
        }
    }

    public function delete(Request $request,$id){
        try {
            $info=KhuDuTruSinhQuyen::where('id',$id)->first();
            KhuDuTruSinhQuyen::find($id)->delete();
            $user = Auth::user();
            createLog($user->id, 'delete_khu_du_tru_sinh_quen', $request->ip(), $request->header('User-Agent'), 'Xóa khu dự trữ sinh quyển '.$info['ten']);
            return response(['message' => 'Done'], 200);
        } catch (\Exception $e) {
            return response(['message' => 'Error'], 501);
        }
    }

    public function getChiSo(){
        $tinhTrang = Lookup::where('group', 'tinhtranghanhlang')->select('id', 'name')->get();

        foreach ($tinhTrang as $item) {
            $item['so_luong'] = KhuDuTruSinhQuyen::where('tinh_trang_id', $item['id'])->count();
        }
        return response(['tinh_trang' => $tinhTrang], 200);
    }

    public function getKhuVuc(Request $request){
        $page = $request->get('page', 1);
        $per_pager = $request->get('perPage', 5);
        $search = $request->get('search', null);
        $tinh_trang_id = $request->get('tinh_trang_id', null);
        $query = KhuDuTruSinhQuyen::with(['tinhTrang', 'khuBaoTon']);
        if ($search != null) {
            $search = trim($search);
            $query = $query->where(function ($query) use ($search) {
                $query->where('ten', 'ilike', "%{$search}%")
                    ->orWhere('ghi_chu', 'ilike', "%{$search}%")
                    ->orWhere('hien_trang_quan_ly', 'ilike', "%{$search}%")
                    ->orWhere('can_cu_de_xuat', 'ilike', "%{$search}%")
                    ->orWhere('mo_ta', 'ilike', "%{$search}%");
            });
        }
        if($tinh_trang_id){
            $query = $query->where('tinh_trang_id', $tinh_trang_id);
        }

        $query->orderBy('updated_at', 'DESC');
        $query->orderBy('id', 'DESC');
        $data =  $query->paginate($per_pager, ['*'], 'page', $page);
        return $data;
    }
}
