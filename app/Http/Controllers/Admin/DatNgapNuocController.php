<?php

namespace App\Http\Controllers\Admin;

use App\DatNgapNuoc;
use App\District;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Lookup;
use App\ProtectedArea;
use App\Province;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class DatNgapNuocController extends Controller
{
    public function viewIndex()
    {
        return view('admin.resources.datngapnuoc.index');
    }
    public function showFormAdd()
    {
        $tinhTrang = Lookup::where('group', 'tinhtranghanhlang')->get();
        $quanHuyen = District::select('name', 'id', 'name_vietnamese')->get();
        $QuanLy = Lookup::where('group', 'phanCap')->get();
        $diaDiem = ProtectedArea::select('id', 'orig_name', 'desig', 'name')->get();
        $doQuanTrong = Lookup::where('group', 'doQuanTrongDatNgapNuoc')->get();
        $danhmucs = [
            'tinh_trang' => $tinhTrang,
            'quan_huyen' => $quanHuyen,
            'phan_cap' => $QuanLy,
            'khu_bao_ton' => $diaDiem,
            'do_quan_trong' => $doQuanTrong
        ];
        return view('admin.resources.datngapnuoc.add', ['danhmucs' => json_encode($danhmucs)]);
    }

    public function showFormEdit($id)
    {
        $tinhTrang = Lookup::where('group', 'tinhtranghanhlang')->get();
        $quanHuyen = District::select('name', 'id', 'name_vietnamese')->get();
        $QuanLy = Lookup::where('group', 'phanCap')->get();
        $diaDiem = ProtectedArea::select('id', 'orig_name', 'desig', 'name')->get();
        $doQuanTrong = Lookup::where('group', 'doQuanTrongDatNgapNuoc')->get();
        $danhmucs = [
            'tinh_trang' => $tinhTrang,
            'quan_huyen' => $quanHuyen,
            'phan_cap' => $QuanLy,
            'khu_bao_ton' => $diaDiem,
            'do_quan_trong' => $doQuanTrong
        ];
        $data = DatNgapNuoc::where('id', $id)->with(['doQuanTrong', 'phanCap', 'khuBaoTon', 'tinhTrang'])->first();
        return view('admin.resources.datngapnuoc.edit', ['danhmucs' => json_encode($danhmucs), 'data' => $data]);
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
            'khu_bao_ton_id',
            'phan_cap_id',
            'co_quan_quan_ly',
            'quoc_te_cong_nhan',
            'danh_hieu_quoc_te',
            'nam_cong_nhan',
            'hinh_thuc_bao_ton',
            'quy_hoach',
            'doi_tuong_bao_ve',
            'khai_thac_su_dung',
            'tieu_chi_dap_ung',
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
            DatNgapNuoc::create($info);
            $user = Auth::user();
            createLog($user->id,'delete_dat_ngap_nuoc',$request->ip(),$request->header('User-Agent'),'Thêm mới đất ngập nước '.$info['ten']);
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
            'van_ban_dieu_chinh',
            'quyet_dinh_thanh_lap',
            'ngay_thanh_lap',
            'co_quan_ban_hanh',
            'khu_bao_ton_id',
            'phan_cap_id',
            'co_quan_quan_ly',
            'quoc_te_cong_nhan',
            'danh_hieu_quoc_te',
            'nam_cong_nhan',
            'hinh_thuc_bao_ton',
            'quy_hoach',
            'doi_tuong_bao_ve',
            'khai_thac_su_dung',
            'tieu_chi_dap_ung',
            'muc_do_quan_trong_id',
            'quan_huyen',
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
            $info['quan_huyen'] =  $info['quan_huyen'] ? json_encode($info['quan_huyen']) : null;
            DatNgapNuoc::find($info['id'])->update($info);
            return response(['message' => 'Done'], 200);
        } catch (\Exception $e) {
            dd($e);
            return response(['message' => 'Không thể thêm mới'], 501);
        }
    }
    public function getKhuVuc(Request $request)
    {
        $page = $request->get('page', 1);
        $per_pager = $request->get('perPage', 5);
        $search = $request->get('search', null);
        $tinh_trang_id = $request->get('tinh_trang_id', null);
        $do_quan_trong_id = $request->get('do_quan_trong_id', null);
        $phan_cap_id = $request->get('phan_cap_id', null);
        $query = DatNgapNuoc::with(['tinhTrang', 'khuBaoTon']);
        if ($search != null) {
            $search = trim($search);
            $query = $query->where(function ($query) use ($search) {
                $query->where('ten', 'ilike', "%{$search}%")
                    ->orWhere('ghi_chu', 'ilike', "%{$search}%")
                    ->orWhere('van_ban_dieu_chinh', 'ilike', "%{$search}%")
                    ->orWhere('quyet_dinh_thanh_lap', 'ilike', "%{$search}%")
                    ->orWhere('khai_thac_su_dung', 'ilike', "%{$search}%")
                    ->orWhere('tieu_chi_ap_dung', 'ilike', "%{$search}%")
                    ->orWhere('ghi_chu', 'ilike', "%{$search}%")
                    ->orWhere('doi_tuong_bao_ve', 'ilike', "%{$search}%")
                    ->orWhere('mo_ta', 'ilike', "%{$search}%");
            });
        }
        if ($tinh_trang_id) {
            $query = $query->where('tinh_trang_id', $tinh_trang_id);
        }
        if ($do_quan_trong_id) {
            $query = $query->where('muc_do_quan_trong_id', $do_quan_trong_id);
        }
        if ($phan_cap_id) {
            $query = $query->where('phan_cap_id', $phan_cap_id);
        }

        $query->orderBy('updated_at', 'DESC');
        $query->orderBy('id', 'DESC');
        $data =  $query->paginate($per_pager, ['*'], 'page', $page);
        return $data;
    }

    public function delete($id)
    {
        try {
            DatNgapNuoc::find($id)->delete();
            return response(['message' => 'Done'], 200);
        } catch (\Exception $e) {
            return response(['message' => 'Error'], 501);
        }
    }

    public function getChiSo()
    {
        $tinhTrang = Lookup::where('group', 'tinhtranghanhlang')->select('id', 'name')->get();
        $doQuanTrong = Lookup::where('group', 'doQuanTrongDatNgapNuoc')->select('id', 'name')->get();
        $QuanLy = Lookup::where('group', 'phanCap')->select('id', 'name')->get();

        foreach ($tinhTrang as $item) {
            $item['so_luong'] = DatNgapNuoc::where('tinh_trang_id', $item['id'])->count();
        }
        foreach ($doQuanTrong as $item) {
            $item['so_luong'] = DatNgapNuoc::where('muc_do_quan_trong_id', $item['id'])->count();
        }
        foreach ($QuanLy as $item) {
            $item['so_luong'] = DatNgapNuoc::where('phan_cap_id', $item['id'])->count();
        }

        return response(['tinh_trang' => $tinhTrang, 'do_quan_trong' => $doQuanTrong, 'quan_ly' => $QuanLy], 200);
    }
}
