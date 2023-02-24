<?php

namespace App\Http\Controllers\Admin;

use App\District;
use App\HanhLangDaDang;
use App\HanhLangKhuBaoTon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Lookup;
use App\ProtectedArea;
use App\Province;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class HanhLangSinhHocController extends Controller
{
    public function viewIndex()
    {
        return view('admin.resources.hanhlang.index');
    }
    public function showFormAdd()
    {
        $tinhTrang = Lookup::where('group', 'tinhtranghanhlang')->get();
        $QuanLy = Lookup::where('group', 'phanCap')->get();
        $diaDiem = ProtectedArea::select('id', 'orig_name', 'desig', 'name')->get();
        $quanHuyen = District::select('name', 'id', 'name_vietnamese')->get();
        $danhmucs = [
            'tinh_trang' => $tinhTrang,
            'quan_ly' => $QuanLy,
            'dia_diem' => $diaDiem,
            'quan_huyen' => $quanHuyen
        ];
        return view('admin.resources.hanhlang.add', ['danhmucs' => json_encode($danhmucs)]);
    }
    public function showFormEdit($id)
    {
        $tinhTrang = Lookup::where('group', 'tinhtranghanhlang')->get();
        $QuanLy = Lookup::where('group', 'phanCap')->get();
        $diaDiem = ProtectedArea::select('id', 'orig_name', 'desig', 'name')->get();
        $quanHuyen = District::select('name', 'id', 'name_vietnamese')->get();
        $danhmucs = [
            'tinh_trang' => $tinhTrang,
            'quan_ly' => $QuanLy,
            'dia_diem' => $diaDiem,
            'quan_huyen' => $quanHuyen
        ];
        $data = HanhLangDaDang::where('id', $id)->with(['quanLy', 'tinhTrang', 'khuBaoTon'])->first();
        return view('admin.resources.hanhlang.edit', ['danhmucs' => json_encode($danhmucs), 'data' => json_encode($data)]);
    }
    public function add(Request $request)
    {
        $info = $request->only(
            'ten',
            'mo_ta',
            'dien_tich',
            'chieu_dai',
            'chuc_nang',
            'quyet_dinh_thanh_lap',
            'ngay_thanh_lap',
            'co_quan_ban_hanh',
            'phan_cap_quan_ly_id',
            'co_quan_quan_ly',
            'muc_dich_thanh_lap',
            'ky_quy_hoach',
            'bien_dong',
            'quy_hoach_tinh',
            'tinh_trang_id',
            'quan_huyen',
            'ghi_chu'
        );
        $khuBaoTon = $request->get('khu_bao_ton_id', null);
        $validator = Validator::make($info, [
            'ten' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'message' => __('Lỗi dữ liệu'),
            ], 401, []);
        }
        try {
            $info['quan_huyen'] = json_encode($info['quan_huyen']);
            DB::beginTransaction();
            $hanhLang = HanhLangDaDang::create($info);
            if ($khuBaoTon && count($khuBaoTon) > 0) {
                foreach ($khuBaoTon as $item) {
                    HanhLangKhuBaoTon::create([
                        'khu_bao_ton_id' => $item,
                        'hanh_lang_id' => $hanhLang->id
                    ]);
                }
            }
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
            'chieu_dai',
            'chuc_nang',
            'quyet_dinh_thanh_lap',
            'ngay_thanh_lap',
            'co_quan_ban_hanh',
            'phan_cap_quan_ly_id',
            'co_quan_quan_ly',
            'muc_dich_thanh_lap',
            'ky_quy_hoach',
            'bien_dong',
            'quy_hoach_tinh',
            'tinh_trang_id',
            'quan_huyen',
            'ghi_chu'
        );
        $khuBaoTon = $request->get('khu_bao_ton_id', null);
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
            $info['quan_huyen'] = json_encode($info['quan_huyen']);
            DB::beginTransaction();
            HanhLangDaDang::find($info['id'])->update($info);
            HanhLangKhuBaoTon::where('hanh_lang_id', $info['id'])->delete();
            if ($khuBaoTon && count($khuBaoTon) > 0) {
                foreach ($khuBaoTon as $item) {
                    HanhLangKhuBaoTon::create([
                        'khu_bao_ton_id' => $item,
                        'hanh_lang_id' => $info['id']
                    ]);
                }
            }
            DB::commit();
            return response(['message' => 'Done'], 200);
        } catch (\Exception $e) {
            dd($e);
            return response(['message' => 'Không thể thêm mới'], 501);
        }
    }
    public function getHanhLang(Request $request)
    {
        $page = $request->get('page', 1);
        $per_pager = $request->get('perPage', 5);
        $search = $request->get('search', null);
        $query = HanhLangDaDang::with(['quanLy', 'tinhTrang']);
        $tinh_trang_id =  $request->get('tinh_trang_id', null);
        $quan_ly_id =  $request->get('quan_ly_id', null);
        if ($search != null) {
            $search = trim($search);
            $query = $query->where(function ($query) use ($search) {
                $query->where('ten', 'ilike', "%{$search}%")
                    ->orWhere('mo_ta', 'ilike', "%{$search}%")
                    ->orWhere('chuc_nang', 'ilike', "%{$search}%")
                    ->orWhere('muc_dich', 'ilike', "%{$search}%")
                    ->orWhere('quan_he_tinh', 'ilike', "%{$search}%")
                    ->orWhere('bien_dong', 'ilike', "%{$search}%")
                    ->orWhere('mo_ta', 'ilike', "%{$search}%");
            });
        }
        if($quan_ly_id){
            $query->where('phan_cap_quan_ly_id', $quan_ly_id);
        }
        if($tinh_trang_id){
            $query->where('tinh_trang_id', $tinh_trang_id);
        }
        $query->orderBy('updated_at', 'DESC');
        $query->orderBy('id', 'DESC');
        $data =  $query->paginate($per_pager, ['*'], 'page', $page);
        return $data;
    }
    public function delete($id){
        try {
            HanhLangDaDang::find($id)->delete();
            return response(['message' => 'Done'], 200);
        } catch (\Exception $e) {
            return response(['message' => 'Error'], 501);
        }
    }

    public function getChiSo(){
        $tinhTrang =  Lookup::where('group', 'tinhtranghanhlang')->select('id', 'name')->get();
        $QuanLy = Lookup::where('group', 'phanCap')->select('id', 'name')->get();

        foreach ($tinhTrang as $item) {
            $item['so_luong'] = HanhLangDaDang::where('tinh_trang_id', $item['id'])->count();
        }
        foreach ($QuanLy as $item) {
            $item['so_luong'] = HanhLangDaDang::where('phan_cap_quan_ly_id', $item['id'])->count();
        }

        return response(['tinh_trang' => $tinhTrang, 'quan_ly' => $QuanLy], 200);
    }
}
