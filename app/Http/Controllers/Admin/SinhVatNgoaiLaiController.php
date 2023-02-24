<?php

namespace App\Http\Controllers\Admin;

use App\DiaDiemSinhVatNgoaiLai;
use App\District;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Lookup;
use App\ProtectedArea;
use App\Province;
use App\SinhVatNgoaiLai;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class SinhVatNgoaiLaiController extends Controller
{
    public function viewIndex()
    {
        return view('admin.category.sinhvatngoailai.index');
    }
    public function showFormAdd()
    {
        $phanLoai = Lookup::where('group', 'phanLoaiSinhVatNgoaiLai')->get();
        $nguyCo = Lookup::where('group', 'nguyCoXamHai')->get();
        $diaDiem = ProtectedArea::select('id', 'orig_name', 'desig', 'name')->get();
        $quanHuyen = District::select('name', 'id', 'name_vietnamese')->get();
        $danhmucs = [
            'phan_loai' => $phanLoai,
            'nguy_co' => $nguyCo,
            'khu_bao_ton' => $diaDiem,
            'quan_huyen' => $quanHuyen
        ];
        return view('admin.category.sinhvatngoailai.add', ['danhmucs' => json_encode($danhmucs)]);
    }
    public function add(Request $request)
    {
        $info = $request->only(
            'ten',
            'ten_khoa_hoc',
            'phan_loai_id',
            'nguy_co_id',
            'nguon_goc',
            'dien_tich_phan_bo',
            'mat_do',
            'don_vi_tinh_mat_do',
            'noi_phan_bo',
            'thoi_gian',
            'nguon_du_lieu',
            'ghi_chu',
            'dac_diem_hinh_thai',
            'dac_diem_sinh_thai',
            'kinh_nghiem_phong_ngua',
            'phan_bo_viet_nam',
            'ghi_nhan_the_gioi',
            'quan_huyen'
        );
        $khuBaoTon = $request->get('dia_diem_id', null);
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
            $svnl = SinhVatNgoaiLai::create($info);
            $user = Auth::user();
            createLog($user->id, 'add_sinh_vat_ngoai_lai', $request->ip(), $request->header('User-Agent'), 'Thêm mới sinh vật ngoại lai');
            if ($khuBaoTon && count($khuBaoTon) > 0) {
                foreach ($khuBaoTon as $item) {
                    DiaDiemSinhVatNgoaiLai::create([
                        'dia_diem_id' => $item,
                        'sinh_vat_ngoai_lai_id' => $svnl->id
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
    public function showFormEdit($id)
    {
        $phanLoai = Lookup::where('group', 'phanLoaiSinhVatNgoaiLai')->get();
        $nguyCo = Lookup::where('group', 'nguyCoXamHai')->get();
        $diaDiem = ProtectedArea::select('id', 'orig_name', 'desig', 'name')->get();
        $quanHuyen = District::select('name', 'id', 'name_vietnamese')->get();
        $danhmucs = [
            'phan_loai' => $phanLoai,
            'nguy_co' => $nguyCo,
            'khu_bao_ton' => $diaDiem,
            'quan_huyen' => $quanHuyen
        ];
        $data = SinhVatNgoaiLai::where('id', $id)->with(['phanLoai', 'nguyCo', 'diaDiem'])->first();
        return view('admin.category.sinhvatngoailai.edit', ['danhmucs' => json_encode($danhmucs), 'data' => json_encode($data)]);
    }
    public function update(Request $request)
    {
        $info = $request->only(
            'id',
            'ten',
            'ten_khoa_hoc',
            'phan_loai_id',
            'nguy_co_id',
            'nguon_goc',
            'dien_tich_phan_bo',
            'mat_do',
            'don_vi_tinh_mat_do',
            'noi_phan_bo',
            'thoi_gian',
            'nguon_du_lieu',
            'ghi_chu',
            'dac_diem_hinh_thai',
            'dac_diem_sinh_thai',
            'kinh_nghiem_phong_ngua',
            'phan_bo_viet_nam',
            'ghi_nhan_the_gioi',
            'quan_huyen'
        );
        $khuBaoTon = $request->get('dia_diem_id', null);
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
            SinhVatNgoaiLai::find($info['id'])->update($info);
            $user = Auth::user();
            createLog($user->id, 'update_sinh_vat_ngoai_lai', $request->ip(), $request->header('User-Agent'), 'Cập nhật sinh vật ngoại lai');
            DiaDiemSinhVatNgoaiLai::where('sinh_vat_ngoai_lai_id', $info['id'])->delete();
            if ($khuBaoTon && count($khuBaoTon) > 0) {
                foreach ($khuBaoTon as $item) {
                    DiaDiemSinhVatNgoaiLai::create([
                        'dia_diem_id' => $item,
                        'sinh_vat_ngoai_lai_id' => $info['id']
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
    public function getSinhVat(Request $request)
    {
        $page = $request->get('page', 1);
        $per_pager = $request->get('perPage', 5);
        $search = $request->get('search', null);
        $query = SinhVatNgoaiLai::with(['phanLoai', 'nguyCo']);
        $phan_loai_id =  $request->get('phan_loai_id', null);
        $nguy_co_id =  $request->get('nguy_co_id', null);
        if ($search != null) {
            $search = trim($search);
            $query = $query->where(function ($query) use ($search) {
                $query->where('ten', 'ilike', "%{$search}%")
                    ->orWhere('ten_khoa_hoc', 'ilike', "%{$search}%")
                    ->orWhere('ghi_chu', 'ilike', "%{$search}%")
                    ->orWhere('noi_phan_bo', 'ilike', "%{$search}%");
            });
        }
        if ($phan_loai_id) {
            $query->where('phan_loai_id', $phan_loai_id);
        }
        if ($nguy_co_id) {
            $query->where('nguy_co_id', $nguy_co_id);
        }
        $query->orderBy('updated_at', 'DESC');
        $query->orderBy('id', 'DESC');
        $data =  $query->paginate($per_pager, ['*'], 'page', $page);
        return $data;
    }
    public function delete(Request $request, $id)
    {
        try {
            $info=SinhVatNgoaiLai::where('id', $id)->first();
            SinhVatNgoaiLai::find($id)->delete();
            $user = Auth::user();
            createLog($user->id, 'delete_sinh_vat_ngoai_lai', $request->ip(), $request->header('User-Agent'), 'Xóa sinh vật ngoại lai');
            return response(['message' => 'Done'], 200);
        } catch (\Exception $e) {
            return response(['message' => 'Error'], 501);
        }
    }

    public function getChiSo()
    {
        $phanLoai =  Lookup::where('group', 'phanLoaiSinhVatNgoaiLai')->select('id', 'name')->get();
        $nguyCo = Lookup::where('group', 'nguyCoXamHai')->select('id', 'name')->get();

        foreach ($nguyCo as $item) {
            $item['so_luong'] = SinhVatNgoaiLai::where('nguy_co_id', $item['id'])->count();
        }
        foreach ($phanLoai as $item) {
            $item['so_luong'] = SinhVatNgoaiLai::where('phan_loai_id', $item['id'])->count();
        }

        return response(['phan_loai' => $phanLoai, 'nguy_co' => $nguyCo], 200);
    }
}
