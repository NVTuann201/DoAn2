<?php

namespace App\Http\Controllers\Admin;

use App\District;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Lookup;
use App\ProtectedArea;
use App\Province;
use App\VungChimQuanTrong;
use Illuminate\Support\Facades\Validator;

class VungChimQuanTrongController extends Controller
{
    public function viewIndex()
    {
        return view('admin.resources.vungchimquantrong.index');
    }
    public function showFormAdd()
    {
        $tinhTrang = Lookup::where('group', 'tinhtranghanhlang')->get();
        $quanHuyen = District::select('name', 'id', 'name_vietnamese')->get();
        $diaDiem = ProtectedArea::select('id', 'orig_name', 'desig', 'name')->get();
        $doQuanTrong = Lookup::where('group', 'doQuanTrongDatNgapNuoc')->get();
        $danhmucs = [
            'tinh_trang' => $tinhTrang,
            'quan_huyen' => $quanHuyen,
            'khu_bao_ton' => $diaDiem,
            'do_quan_trong' => $doQuanTrong
        ];
        return view('admin.resources.vungchimquantrong.add', ['danhmucs' => json_encode($danhmucs)]);
    }

    public function showFormEdit($id)
    {
        $tinhTrang = Lookup::where('group', 'tinhtranghanhlang')->get();
        $quanHuyen = District::select('name', 'id', 'name_vietnamese')->get();
        $diaDiem = ProtectedArea::select('id', 'orig_name', 'desig', 'name')->get();
        $doQuanTrong = Lookup::where('group', 'doQuanTrongDatNgapNuoc')->get();
        $danhmucs = [
            'tinh_trang' => $tinhTrang,
            'quan_huyen' => $quanHuyen,
            'khu_bao_ton' => $diaDiem,
            'do_quan_trong' => $doQuanTrong
        ];
        $data = VungChimQuanTrong::where('id', $id)->with(['tinhTrang', 'khuBaoTon', 'doQuanTrong'])->first();
        return view('admin.resources.vungchimquantrong.edit', ['danhmucs' => json_encode($danhmucs), 'data' => json_encode($data)]);
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
            'vung_chim_dac_huu',
            'canh_quan_uu_tien',
            'sinh_canh',
            'khu_bao_ton_id',
            'muc_do_quan_trong_id',
            'hien_trang_quan_ly',
            'phan_cap_quan_ly',
            'co_quan_quan_ly',
            'hoat_dong_bao_ton',
            'quoc_te_cong_nhan',
            'danh_hieu_quoc_te',
            'thoi_gian_cong_nhan',
            'quan_huyen',
            'quy_hoach_tinh',
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
            VungChimQuanTrong::create($info);
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
            'vung_chim_dac_huu',
            'canh_quan_uu_tien',
            'sinh_canh',
            'khu_bao_ton_id',
            'muc_do_quan_trong_id',
            'hien_trang_quan_ly',
            'phan_cap_quan_ly',
            'co_quan_quan_ly',
            'hoat_dong_bao_ton',
            'quoc_te_cong_nhan',
            'danh_hieu_quoc_te',
            'thoi_gian_cong_nhan',
            'quan_huyen',
            'quy_hoach_tinh',
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
            VungChimQuanTrong::find($info['id'])->update($info);
            return response(['message' => 'Done'], 200);
        } catch (\Exception $e) {
            dd($e);
            return response(['message' => 'Không thể cập nhật'], 501);
        }
    }

    public function delete($id){
        try {
            VungChimQuanTrong::find($id)->delete();
            return response(['message' => 'Done'], 200);
        } catch (\Exception $e) {
            return response(['message' => 'Error'], 501);
        }
    }

    public function getChiSo(){
        $tinhTrang = Lookup::where('group', 'tinhtranghanhlang')->select('id', 'name')->get();
        $doQuanTrong = Lookup::where('group', 'doQuanTrongDatNgapNuoc')->select('id', 'name')->get();

        foreach ($tinhTrang as $item) {
            $item['so_luong'] = VungChimQuanTrong::where('tinh_trang_id', $item['id'])->count();
        }
        foreach ($doQuanTrong as $item) {
            $item['so_luong'] = VungChimQuanTrong::where('muc_do_quan_trong_id', $item['id'])->count();
        }
        return response(['tinh_trang' => $tinhTrang, 'do_quan_trong' => $doQuanTrong], 200);
    }

    public function getVungChim(Request $request){
        $page = $request->get('page', 1);
        $per_pager = $request->get('perPage', 5);
        $search = $request->get('search', null);
        $tinh_trang_id = $request->get('tinh_trang_id', null);
        $do_quan_trong_id = $request->get('do_quan_trong_id', null);
        $query = VungChimQuanTrong::with(['tinhTrang', 'khuBaoTon', 'doQuanTrong']);
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
        if($do_quan_trong_id){
            $query = $query->where('muc_do_quan_trong_id', $do_quan_trong_id);
        }

        $query->orderBy('updated_at', 'DESC');
        $query->orderBy('id', 'DESC');
        $data =  $query->paginate($per_pager, ['*'], 'page', $page);
        return $data;
    }
}
