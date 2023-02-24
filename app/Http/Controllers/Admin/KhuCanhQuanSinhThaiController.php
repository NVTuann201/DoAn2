<?php

namespace App\Http\Controllers\Admin;

use App\District;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\KhuCanhQuanSinhThai;
use App\Lookup;
use App\ProtectedArea;
use App\Province;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class KhuCanhQuanSinhThaiController extends Controller
{
    public function viewIndex()
    {
        return view('admin.resources.khucanhquansinhthai.index');
    }
    public function showFormAdd()
    {
        $tinhTrang = Lookup::where('group', 'tinhtranghanhlang')->get();
        $quanHuyen = District::select('name', 'id', 'name_vietnamese')->get();
        $doQuanTrong = Lookup::where('group', 'doQuanTrongDatNgapNuoc')->get();
        $danhmucs = [
            'tinh_trang' => $tinhTrang,
            'quan_huyen' => $quanHuyen,
            'do_quan_trong' => $doQuanTrong
        ];
        return view('admin.resources.khucanhquansinhthai.add', ['danhmucs' => json_encode($danhmucs)]);
    }

    public function add(Request $request)
    {
        $info = $request->only(
            'ten',
            'mo_ta',
            'dien_tich',
            'tinh_trang_id',
            'van_ban_dieu_chinh',
            'quyet_dinh_thanh_lap',
            'ngay_thanh_lap',
            'co_quan_ban_hanh',
            'co_quan_quan_ly',
            'phan_cap_quan_ly',
            'de_xuat_quan_ly',
            'quoc_te_cong_nhan',
            'danh_hieu_quoc_te',
            'thoi_gian_cong_nhan',
            'muc_do_quan_trong_id',
            'quan_huyen',
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
            $info['quan_huyen'] =  $info['quan_huyen'] ? json_encode($info['quan_huyen']) : null;
            KhuCanhQuanSinhThai::create($info);
            $user = Auth::user();
            createLog($user->id, 'add_khu_canh_quan', $request->ip(), $request->header('User-Agent'), 'Thêm mới khu cảnh quan '.$info['ten']);
            return response(['message' => 'Done'], 200);
        } catch (\Exception $e) {
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
            'van_ban_dieu_chinh',
            'quyet_dinh_thanh_lap',
            'ngay_thanh_lap',
            'co_quan_ban_hanh',
            'co_quan_quan_ly',
            'phan_cap_quan_ly',
            'de_xuat_quan_ly',
            'quoc_te_cong_nhan',
            'danh_hieu_quoc_te',
            'thoi_gian_cong_nhan',
            'muc_do_quan_trong_id',
            'quan_huyen',
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
            $info['quan_huyen'] =  $info['quan_huyen'] ? json_encode($info['quan_huyen']) : null;
            KhuCanhQuanSinhThai::find($info['id'])->update($info);
            $user = Auth::user();
            createLog($user->id, 'add_khu_canh_quan', $request->ip(), $request->header('User-Agent'), 'Thêm mới khu cảnh quan '.$info['ten']);
            return response(['message' => 'Done'], 200);
        } catch (\Exception $e) {

            return response(['message' => 'Không thể thêm mới'], 501);
        }
    }

    public function delete(Request $request, $id)
    {
        try {
            $info=KhuCanhQuanSinhThai::where('id', $id)->first();
            KhuCanhQuanSinhThai::find($id)->delete();
            $user = Auth::user();
            createLog($user->id, 'delete_khu_canh_quan', $request->ip(), $request->header('User-Agent'), 'Xóa khu cảnh quan '.$info['ten']);
            return response(['message' => 'Done'], 200);
        } catch (\Exception $e) {
            return response(['message' => 'Error'], 501);
        }
    }

    public function getChiSo()
    {
        $tinhTrang = Lookup::where('group', 'tinhtranghanhlang')->select('id', 'name')->get();
        $doQuanTrong = Lookup::where('group', 'doQuanTrongDatNgapNuoc')->select('id', 'name')->get();

        foreach ($tinhTrang as $item) {
            $item['so_luong'] = KhuCanhQuanSinhThai::where('tinh_trang_id', $item['id'])->count();
        }
        foreach ($doQuanTrong as $item) {
            $item['so_luong'] = KhuCanhQuanSinhThai::where('muc_do_quan_trong_id', $item['id'])->count();
        }
        return response(['tinh_trang' => $tinhTrang, 'do_quan_trong' => $doQuanTrong], 200);
    }

    public function getKhuVuc(Request $request)
    {
        $page = $request->get('page', 1);
        $per_pager = $request->get('perPage', 5);
        $search = $request->get('search', null);
        $tinh_trang_id = $request->get('tinh_trang_id', null);
        $do_quan_trong_id = $request->get('do_quan_trong_id', null);
        $query = KhuCanhQuanSinhThai::with(['tinhTrang', 'doQuanTrong']);
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
        if ($tinh_trang_id) {
            $query = $query->where('tinh_trang_id', $tinh_trang_id);
        }
        if ($do_quan_trong_id) {
            $query = $query->where('muc_do_quan_trong_id', $do_quan_trong_id);
        }

        $query->orderBy('updated_at', 'DESC');
        $query->orderBy('id', 'DESC');
        $data =  $query->paginate($per_pager, ['*'], 'page', $page);
        return $data;
    }

    public function showFormEdit($id)
    {
        $tinhTrang = Lookup::where('group', 'tinhtranghanhlang')->get();
        $quanHuyen = District::select('name', 'id', 'name_vietnamese')->get();
        $doQuanTrong = Lookup::where('group', 'doQuanTrongDatNgapNuoc')->get();
        $danhmucs = [
            'tinh_trang' => $tinhTrang,
            'quan_huyen' => $quanHuyen,
            'do_quan_trong' => $doQuanTrong
        ];
        $data = KhuCanhQuanSinhThai::where('id', $id)->with(['tinhTrang', 'doQuanTrong'])->first();
        return view('admin.resources.khucanhquansinhthai.edit', ['danhmucs' => json_encode($danhmucs), 'data' => json_encode($data)]);
    }
}
