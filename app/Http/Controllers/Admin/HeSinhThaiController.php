<?php

namespace App\Http\Controllers\Admin;

use App\District;
use App\HeSinhThai;
use App\Http\Controllers\Controller;
use App\Lookup;
use App\ProtectedArea;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class HeSinhThaiController extends Controller
{
    public function viewIndex()
    {
        return view('admin.resources.hesinhthai.index');
    }
    public function showFormAdd()
    {
        $loaiHesinhThaiNuoc = Lookup::where('group', 'phanloaihesinhthainuoc')->get();
        $loaiHesinhThaiCan = Lookup::where('group', 'phanloairunghesinhthaican')->get();
        $loaiHeSinhThaiDanCu = Lookup::where('group', 'phanloaihesinhthaidancu')->get();
        $hst = Lookup::where('group', 'hesinhthai')->get();
        $diaDiem = ProtectedArea::select('id', 'orig_name', 'desig', 'name')->get();
        $quanHuyen = District::select('name', 'id', 'name_vietnamese')->get();
        $danhmucs = [
            'he_sinh_thai' => $hst,
            'loai_hst_nuoc' => $loaiHesinhThaiNuoc,
            'loai_hst_can' => $loaiHesinhThaiCan,
            'loai_hst_can_cu' => $loaiHeSinhThaiDanCu,
            'dia_diem' => $diaDiem,
            'quan_huyen' => $quanHuyen,
        ];
        return view('admin.resources.hesinhthai.add', ['danhmucs' => json_encode($danhmucs)]);
    }
    public function showFormEdit($id)
    {
        $loaiHesinhThaiNuoc = Lookup::where('group', 'phanloaihesinhthainuoc')->get();
        $loaiHesinhThaiCan = Lookup::where('group', 'phanloairunghesinhthaican')->get();
        $loaiHeSinhThaiDanCu = Lookup::where('group', 'phanloaihesinhthaidancu')->get();
        $hst = Lookup::where('group', 'hesinhthai')->get();
        $diaDiem = ProtectedArea::select('id', 'orig_name', 'desig', 'name')->get();
        $quanHuyen = District::select('name', 'id', 'name_vietnamese')->get();
        $danhmucs = [
            'he_sinh_thai' => $hst,
            'loai_hst_nuoc' => $loaiHesinhThaiNuoc,
            'loai_hst_can' => $loaiHesinhThaiCan,
            'loai_hst_can_cu' => $loaiHeSinhThaiDanCu,
            'dia_diem' => $diaDiem,
            'quan_huyen' => $quanHuyen
        ];
        $data = HeSinhThai::where('id', $id)->with(['heSinhThai', 'diaDiem', 'phanLoai'])->first();
        return view('admin.resources.hesinhthai.edit', ['danhmucs' => json_encode($danhmucs), 'data' => json_encode($data)]);
    }
    public function add(Request $request)
    {
        $info = $request->only(
            'he_sinh_thai_lookup_id',
            'ten',
            'dien_tich',
            'dien_tich_chuyen_doi',
            'dia_diem_id',
            'phan_loai_id',
            'nguon_goc_id',
            'phan_loai_rung_id',
            'phan_loai_he_sinh_thai_id',
            'do_phu',
            'tru_luong',
            'don_vi_tinh_tru_luong',
            'dien_tich_rung_trong_moi',
            'dien_tich_moi_chet',
            'dien_tich_rung_phong_ho',
            'dien_tich_rung_fsc',
            'dien_tich_rung_dvtm',
            'nguon_tai_lieu',
            'mo_ta',
            'quan_huyen',
            'ky_bao_cao',
            'bat_dau',
            'ket_thuc',
            'dien_tich_rung_dac_dung',
            'dien_tich_rung_tu_nhien',
            'dien_tich_rung_trong',
            'dien_tich_rung_chuyen_doi',
            'dien_tich_dnn_chuyen_doi',
            'dien_tich_rung_ngap_nuoc',
            'dien_tich_song_ngoi',
            'dien_tich_dat_mat_nuoc',
            'geom'
        );
        $validator = Validator::make($info, [
            'he_sinh_thai_lookup_id' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'message' => __('Lỗi dữ liệu'),
            ], 401, []);
        }
        if(isset($info['bat_dau']) && isset($info['ket_thuc']) && $info['bat_dau'] && $info['ket_thuc'] && strtotime($info['bat_dau']) > strtotime($info['ket_thuc'])){
            return response()->json([
                'message' => __('datetime'),
            ], 200, []);
        }
        $info['quan_huyen'] = json_encode($info['quan_huyen']);
        $info['geom'] = $info['geom'] ? DB::raw("ST_GeomFromGeoJSON('" . json_encode($info['geom']) . "')") : null;
        HeSinhThai::create($info);
        $user = Auth::user();
        createLog($user->id, 'add_hst', $request->ip(), $request->header('User-Agent'), 'Thêm mới hệ sinh thái '.$info['ten']);
        return response(['message' => 'Done'], 200);
    }
    public function edit(Request $request)
    {
        $info = $request->only(
            'id',
            'he_sinh_thai_lookup_id',
            'ten',
            'dien_tich',
            'dien_tich_chuyen_doi',
            'dia_diem_id',
            'phan_loai_id',
            'nguon_goc_id',
            'phan_loai_rung_id',
            'phan_loai_he_sinh_thai_id',
            'do_phu',
            'tru_luong',
            'don_vi_tinh_tru_luong',
            'dien_tich_rung_trong_moi',
            'dien_tich_moi_chet',
            'dien_tich_rung_phong_ho',
            'dien_tich_rung_fsc',
            'dien_tich_rung_dvtm',
            'nguon_tai_lieu',
            'mo_ta',
            'quan_huyen',
            'ky_bao_cao',
            'bat_dau',
            'ket_thuc',
            'dien_tich_rung_dac_dung',
            'dien_tich_rung_tu_nhien',
            'dien_tich_rung_trong',
            'dien_tich_rung_chuyen_doi',
            'dien_tich_dnn_chuyen_doi',
            'dien_tich_rung_ngap_nuoc',
            'dien_tich_song_ngoi',
            'dien_tich_dat_mat_nuoc',
            'geom'
        );
        $validator = Validator::make($info, [
            'he_sinh_thai_lookup_id' => 'required',
            'id' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'message' => __('Lỗi dữ liệu'),
            ], 401, []);
        }
        if(isset($info['bat_dau']) && isset($info['ket_thuc']) && $info['bat_dau'] && $info['ket_thuc'] && strtotime($info['bat_dau']) > strtotime($info['ket_thuc'])){
            return response()->json([
                'message' => __('datetime'),
            ], 200, []);
        }
        try {
            $info['quan_huyen'] = json_encode($info['quan_huyen']);
            $info['geom'] = $info['geom'] ? DB::raw("ST_GeomFromGeoJSON('" . json_encode($info['geom']) . "')") : null;
            HeSinhThai::find($info['id'])->update($info);
            $user = Auth::user();
            createLog($user->id, 'update_hst', $request->ip(), $request->header('User-Agent'), 'Cập nhật hệ sinh thái '.$info['ten']);
            return response(['message' => 'Done'], 200);
        } catch (\Exception $e) {
            dd($e);
            return response(['message' => 'Không thể cập nhật'], 501);
        }
    }
    public function getHeSinhThai(Request $request)
    {
        $page = $request->get('page', 1);
        $per_pager = $request->get('perPage', 5);
        $search = $request->get('search', null);
        $he_sinh_thai_id = $request->get('he_sinh_thai_id', null);
        $khu_bao_ton_id = $request->get('khu_bao_ton_id', null);
        $query = HeSinhThai::with(['heSinhThai', 'diaDiem']);
        if ($search != null) {
            $search = trim($search);
            $query = $query->where(function ($query) use ($search) {
                $query->where('ten', 'ilike', "%{$search}%")
                    ->orWhere('nguon_tai_lieu', 'ilike', "%{$search}%")
                    ->orWhere('mo_ta', 'ilike', "%{$search}%");
            });
        }
        if ($he_sinh_thai_id) {
            $query->where('he_sinh_thai_lookup_id', $he_sinh_thai_id);
        }
        if($khu_bao_ton_id){
            $query = $query->where('loai_doi_tuong', 'khu_bao_ton')->where('doi_tuong_id', $khu_bao_ton_id);
        }
        $query->orderBy('updated_at', 'DESC');
        $query->orderBy('id', 'DESC');
        $data = $query->paginate($per_pager, ['*'], 'page', $page);
        return $data;
    }

    public function delete(Request $request, $id)
    {
        try {
            $info=HeSinhThai::where('id', $id)->first();
            HeSinhThai::find($id)->delete();
            $user = Auth::user();
            createLog($user->id, 'add_hst', $request->ip(), $request->header('User-Agent'), 'Thêm mới hệ sinh thái '.$info['ten']);
            return response(['message' => 'Done'], 200);
        } catch (\Exception $e) {
            return response(['message' => 'Error'], 501);
        }
    }

    public function getChiSo()
    {
        $heSinhThai = Lookup::where('group', 'hesinhthai')->select('id', 'name')->get();

        foreach ($heSinhThai as $item) {
            $item['so_luong'] = HeSinhThai::where('he_sinh_thai_lookup_id', $item['id'])->count();
        }
        return response(['he_sinh_thai' => $heSinhThai], 200);
    }
}
