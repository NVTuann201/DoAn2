<?php

namespace App\Http\Controllers\admin;

use App\DiemQuanTrac;
use App\Http\Controllers\Controller;
use App\KetQuaQuanTrac;
use App\LoaiHinhQuanTrac;
use App\ProtectedArea;
use App\ThongSo;
use App\ThongSoQuanTrac;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class QuanTracController extends Controller
{
    public function viewIndex()
    {
        return view('admin.category.quantrac.index');
    }
    public function addLoaiHinh(Request $request)
    {
        $info = $request->only('ten', 'mo_ta');
        $validator = Validator::make($info, [
            'ten' => 'required|max:255',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'message' => __('Lỗi dữ liệu'),
            ], 401, []);
        }
        try {

            LoaiHinhQuanTrac::create($info);
            return response(['message' => 'Done'], 200);
        } catch (\Exception $e) {
            return response(['message' => 'Không thể thêm mới'], 501);
        }
    }
    public function editLoaiHinh(Request $request)
    {
        $info = $request->only('ten', 'mo_ta', 'id');
        $validator = Validator::make($info, [
            'ten' => 'required|max:255',
            'id' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'message' => __('Lỗi dữ liệu'),
            ], 401, []);
        }
        try {

            LoaiHinhQuanTrac::where('id', $info['id'])->first()->update($info);
            return response(['message' => 'Done'], 200);
        } catch (\Exception $e) {
            return response(['message' => 'Không thể Cập nhật'], 501);
        }
    }
    public function getLoaiHinh(Request $request)
    {
        $page = $request->get('page', 1);
        $per_pager = $request->get('perPage', 5);
        $search = $request->get('search', null);
        $query = LoaiHinhQuanTrac::query();
        if ($search != null) {
            $search = trim($search);
            $query = $query->where(function ($query) use ($search) {
                $query->where('ten', 'ilike', "%{$search}%")
                    ->orWhere('mo_ta', 'ilike', "%{$search}%");
            });
        }
        $query->orderBy('updated_at', 'DESC');
        $query->orderBy('id', 'DESC');
        $data = $query->paginate($per_pager, ['*'], 'page', $page);
        return $data;
    }

    public function addThongSo(Request $request)
    {
        $info = $request->only('ten_tieng_anh', 'ten_tieng_viet', 'ky_hieu_hoa_hoc', 'mo_ta');
        $validator = Validator::make($info, [
            'ten_tieng_viet' => 'required|max:255',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'message' => __('Lỗi dữ liệu'),
            ], 401, []);
        }
        try {

            ThongSoQuanTrac::create($info);
            return response(['message' => 'Done'], 200);
        } catch (\Exception $e) {
            return response(['message' => 'Không thể thêm mới'], 501);
        }
    }

    public function editThongSo(Request $request)
    {
        $info = $request->only('ten_tieng_anh', 'ten_tieng_viet', 'ky_hieu_hoa_hoc', 'mo_ta', 'id');
        $validator = Validator::make($info, [
            'ten_tieng_viet' => 'required|max:255',
            'id' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'message' => __('Lỗi dữ liệu'),
            ], 401, []);
        }
        try {

            ThongSoQuanTrac::find($info['id'])->update($info);
            return response(['message' => 'Done'], 200);
        } catch (\Exception $e) {
            return response(['message' => 'Không thể cập nhật'], 501);
        }
    }

    public function getThongSo(Request $request)
    {
        $page = $request->get('page', 1);
        $per_pager = $request->get('perPage', 5);
        $search = $request->get('search', null);
        $query = ThongSoQuanTrac::query();
        if ($search != null) {
            $search = trim($search);
            $query = $query->where(function ($query) use ($search) {
                $query->where('ten_tieng_anh', 'ilike', "%{$search}%")
                    ->where('ten_tieng_viet', 'ilike', "%{$search}%")
                    ->where('ky_hieu_hoa_hoc', 'ilike', "%{$search}%")
                    ->orWhere('mo_ta', 'ilike', "%{$search}%");
            });
        }
        $query->orderBy('updated_at', 'DESC');
        $query->orderBy('id', 'DESC');
        $data = $query->paginate($per_pager, ['*'], 'page', $page);
        return $data;
    }

    public function xoaThongSo($id)
    {
        try {
            ThongSoQuanTrac::find($id)->delete();
            return response(['message' => 'Done'], 200);
        } catch (\Exception $e) {
            return response(['Không thể xóa dữ liệu'], 501);
        }
    }
    public function xoaLoaiHinh($id)
    {
        try {
            LoaiHinhQuanTrac::find($id)->delete();
            return response(['message' => 'Done'], 200);
        } catch (\Exception $e) {
            return response(['Không thể xóa dữ liệu'], 501);
        }
    }

    public function getKhuBaoTon()
    {
        return ProtectedArea::select('id', 'orig_name', 'desig')->get();
    }

    public function addDiemQuanTrac(Request $request)
    {
        $info = $request->only(
            'ten',
            'ky_hieu_mau',
            'loai_hinh_id',
            'thoi_gian',
            'khu_bao_ton_id',
            'ket_qua',
            'don_vi_quan_trac',
            'mo_ta',
            'ghi_chu',
            'geom'
        );
        $validator = Validator::make($info, [
            'ten' => 'required|max:255',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'message' => __('Lỗi dữ liệu'),
            ], 401, []);
        }
        try {
            $info['geom'] = $info['geom'] ? DB::raw("ST_GeomFromGeoJSON('" . json_encode($info['geom']) . "')") : null;

            DiemQuanTrac::create($info);
            return response(['message' => 'Done'], 200);
        } catch (\Exception $e) {
            return response(['message' => 'Không thể thêm mới'], 501);
        }
    }
    public function editDiemQuanTrac(Request $request)
    {
        $info = $request->only(
            'id',
            'ten',
            'ky_hieu_mau',
            'loai_hinh_id',
            'thoi_gian',
            'khu_bao_ton_id',
            'ket_qua',
            'don_vi_quan_trac',
            'mo_ta',
            'ghi_chu',
            'geom'
        );
        $validator = Validator::make($info, [
            'ten' => 'required|max:255',
            'id' => 'required|max:255',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'message' => __('Lỗi dữ liệu'),
            ], 401, []);
        }
        try {
            $info['geom'] = $info['geom'] ? DB::raw("ST_GeomFromGeoJSON('" . json_encode($info['geom']) . "')") : null;
            DiemQuanTrac::find($info['id'])->update($info);
            return response(['message' => 'Done'], 200);
        } catch (\Exception $e) {
            return response(['message' => 'Không thể thêm mới'], 501);
        }
    }
    public function getDiemQuanTrac(Request $request)
    {
        $page = $request->get('page', 1);
        $per_pager = $request->get('perPage', 5);
        $search = $request->get('search', null);
        $query = DiemQuanTrac::with(['khuBaoTon', 'loaiHinh']);
        if ($search != null) {
            $search = trim($search);
            $query = $query->where(function ($query) use ($search) {
                $query->where('ten', 'ilike', "%{$search}%")
                    ->where('ghi_chu', 'ilike', "%{$search}%")
                    ->where('ket_qua', 'ilike', "%{$search}%")
                    ->orWhere('mo_ta', 'ilike', "%{$search}%");
            });
        }
        $query->orderBy('updated_at', 'DESC');
        $query->orderBy('id', 'DESC');
        $data = $query->paginate($per_pager, ['*'], 'page', $page);
        return $data;
    }
    public function xoaDiemQuanTrac($id)
    {
        try {
            DiemQuanTrac::find($id)->delete();
            return response(['message' => 'Done'], 200);
        } catch (\Exception $e) {
            return response(['Không thể xóa dữ liệu'], 501);
        }
    }

    public function addKetQua(Request $request)
    {
        $info = $request->only(
            'diem_quan_trac_id',
            'thong_so_id',
            'ket_qua',
            'don_vi_do',
            'quy_chuan_viet_nam',
            'ghi_chu',
            'files'
        );
        $validator = Validator::make($info, [
            'diem_quan_trac_id' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'message' => __('Lỗi dữ liệu'),
            ], 401, []);
        }
        try {
            if (isset($info['files']) && is_array($info['files'])) {
                $info['files'] = json_encode($info['files']);
            } else {
                $info['files'] = null;
            }
            KetQuaQuanTrac::create($info);
            return response(['message' => 'Done'], 200);
        } catch (\Exception $e) {
            return response(['message' => 'Không thể thêm mới'], 501);
        }
    }

    public function editKetQua(Request $request)
    {
        $info = $request->only(
            'id',
            'diem_quan_trac_id',
            'thong_so_id',
            'ket_qua',
            'don_vi_do',
            'quy_chuan_viet_nam',
            'ghi_chu',
            'files'
        );
        $validator = Validator::make($info, [
            'diem_quan_trac_id' => 'required',
            'id' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'message' => __('Lỗi dữ liệu'),
            ], 401, []);
        }
        try {
            if (isset($info['files']) && is_array($info['files'])) {
                $info['files'] = json_encode($info['files']);
            } else {
                $info['files'] = null;
            }
            KetQuaQuanTrac::find($info['id'])->update($info);
            return response(['message' => 'Done'], 200);
        } catch (\Exception $e) {
            return response(['message' => 'Không thể thêm mới'], 501);
        }
    }

    public function getKetQua(Request $request)
    {
        $page = $request->get('page', 1);
        $per_pager = $request->get('perPage', 5);
        $search = $request->get('search', null);
        $query = KetQuaQuanTrac::with(['diemQuanTrac', 'thongSo']);
        if ($search != null) {
            $search = trim($search);
            $query = $query->where(function ($query) use ($search) {
                $query->where('quy_chuan_viet_nam', 'ilike', "%{$search}%")
                    ->orWhere('ghi_chu', 'ilike', "%{$search}%")
                    ->orWhereHas('thongSo', function ($query) use ($search) {
                        $query->where('ten', 'ilike', "%{$search}%");
                    })
                    ->orWhereHas('diemQuanTrac', function ($query) use ($search) {
                        $query->where('ten', 'ilike', "%{$search}%");
                    });
            });
        }
        $query->orderBy('updated_at', 'DESC');
        $query->orderBy('id', 'DESC');
        $data = $query->paginate($per_pager, ['*'], 'page', $page);
        foreach ($data as $item) {
            $files = [];
            $fileIds = json_decode($item['files']);
            if ($item['files'] && count($fileIds) > 0) {
                $files = DB::table('media')->whereIn('id', $fileIds)->select('id', 'name', 'disk', 'size')->get();
                $item['fileList'] = $files->toArray();
            } else {
                $item['fileList'] = [];
            }

        }
        return $data;
    }
    public function deleteKetQua($id)
    {
        try {
            KetQuaQuanTrac::find($id)->delete();
            return response(['message' => 'Done'], 200);
        } catch (\Exception $e) {
            return response(['Không thể xóa dữ liệu'], 501);
        }
    }
    public function getDanhMucKetQuaQuanTrac()
    {
        $thongSo = ThongSo::get();
        $diemQuanTrac = DiemQuanTrac::get();
        return response(['thong_so' => $thongSo, 'diem_quan_trac' => $diemQuanTrac], 200);
    }
}
