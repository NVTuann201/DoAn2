<?php

namespace App\Http\Controllers\admin;

use App\GiayPhepDaDangSinhHoc;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Lookup;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class GiayPhepController extends Controller
{
    public function viewIndex()
    {
        $giayPhep = Lookup::where('group', 'GiayPhepDDSH')->select('name', 'id')->get()->toArray();
        $loaiGiay = Lookup::where('group', 'LoaiGiayPhep')->select('name', 'id')->get()->toArray();
        return view('admin.resources.giayphep.index', ['tenGiayPheps' => json_encode($giayPhep), 'loaiGiays' => json_encode($loaiGiay)]);
    }

    public function addGiayPhep(Request $request)
    {
        $info = $request->only('ten_giay_phep_id', 'co_quan_cap', 'ngay_cap', 'loai_giay_phep_id', 'don_vi_duoc_cap', 'ngay_het_han', 'ghi_chu', 'files', 'ghi_chu');
        $validator = Validator::make($info, [
            'ten_giay_phep_id' => 'required|max:255',
            'loai_giay_phep_id' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'message' => __('Lỗi dữ liệu'),
            ], 401, []);
        }
        if (isset($info['files']) && is_array($info['files'])) {
            $info['files'] = json_encode($info['files']);
        } else {
            $info['files'] = null;
        }
        try {
            GiayPhepDaDangSinhHoc::create($info);
            $user = Auth::user();
            createLog($user->id, 'add_giay_phep', $request->ip(), $request->header('User-Agent'), 'Thêm mới giấy phép');
            return response(['message' => 'Done'], 200);
        } catch (\Exception $e) {
            return response(['message' => 'Không thể thêm mới'], 501);
        }
    }
    public function getGiayPhep(Request $request)
    {
        $page = $request->get('page', 1);
        $per_pager = $request->get('perPage', 5);
        $search = $request->get('search', null);
        $loai_giay = $request->get('loai_giay', null);
        $nam_cap_giay = $request->get('nam_cap_giay', null);
        $nam_het_han = $request->get('nam_het_han', null);
        $query = GiayPhepDaDangSinhHoc::with(['loaiGiayPhep', 'loaiCap']);
        if ($search != null) {
            $search = trim($search);
            $query = $query->where(function ($query) use ($search) {
                $query->where('co_quan_cap', 'ilike', "%{$search}%")
                    ->orWhere('don_vi_duoc_cap', 'ilike', "%{$search}%")
                    ->orWhere('ghi_chu', 'ilike', "%{$search}%")
                    ->orWhereHas('tenGiayPhep', function ($query) use ($search) {
                        $query->where('name', 'ilike', "%{$search}%");
                    });
            });
        }
        if ($nam_cap_giay != null) {
            $query->whereYear('ngay_cap', $nam_cap_giay);
        }
        if ($nam_het_han != null) {
            $query->whereYear('ngay_het_han', $nam_het_han);
        }
        if ($loai_giay != null) {
            $query->where('loai_cap_phep_id', $loai_giay);
        }

        $query->orderBy('updated_at', 'DESC');
        $query->orderBy('id', 'DESC');
        $data =  $query->paginate($per_pager, ['*'], 'page', $page);
        foreach ($data as $item) {
            $files = [];
            $fileIds = json_decode($item['files']);
            if ($item['files'] && count($fileIds) > 0) {
                $files = DB::table('media')->whereIn('id', $fileIds)->select('id', 'name', 'disk', 'size')->get();
                $item['fileList'] = $files->toArray();
            } else $item['fileList'] = [];
        }
        return $data;
    }

    public function editGiayPhep(Request $request)
    {
        $info = $request->only('id', 'ten_giay_phep_id', 'co_quan_cap', 'ngay_cap', 'loai_giay_phep_id', 'don_vi_duoc_cap', 'ngay_het_han', 'ghi_chu', 'files', 'ghi_chu');
        $validator = Validator::make($info, [
            'id' => 'required',
            'ten_giay_phep_id' => 'required|max:255',
            'loai_giay_phep_id' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'message' => __('Lỗi dữ liệu'),
            ], 401, []);
        }
        if ($info['files']) {
            $info['files'] = json_encode($info['files']);
        }else $info['files'] = null;
        try {
            GiayPhepDaDangSinhHoc::find($info['id'])->update($info);
            $user = Auth::user();
            createLog($user->id, 'update_giay_phep', $request->ip(), $request->header('User-Agent'), 'Cập nhật giấy phép');
            return response(['message' => 'Done'], 200);
        } catch (\Exception $e) {
            return response(['message' => 'Không thể cập nhật'], 501);
        }
    }

    public function showFormAdd()
    {
        $loaiGiayPhep = Lookup::where('group', 'loaiGiayPhepDaDangSinhHoc')->get();
        $doiTuong = Lookup::where('group', 'doiTuongCapGiayDdsh')->get();
        $loaiCap = Lookup::where('group', 'LoaiGiayPhep')->get();
        $mucDich = Lookup::where('group', 'mucDichCapGiayDdsh')->get();
        $data = [
            'loai_giay' => $loaiGiayPhep,
            'doi_tuong' => $doiTuong,
            'loai_cap' => $loaiCap,
            'muc_dich' => $mucDich
        ];
        return view('admin.resources.giayphep.add', ['danhmucs' => json_encode($data)]);
    }
    public function showFormEdit($id)
    {
        $loaiGiayPhep = Lookup::where('group', 'loaiGiayPhepDaDangSinhHoc')->get();
        $doiTuong = Lookup::where('group', 'doiTuongCapGiayDdsh')->get();
        $loaiCap = Lookup::where('group', 'LoaiGiayPhep')->get();
        $mucDich = Lookup::where('group', 'mucDichCapGiayDdsh')->get();
        $data = GiayPhepDaDangSinhHoc::where('id', $id)->with(['loaiGiayPhep', 'loaiCap', 'doiTuong', 'mucDich'])->first();
        $danhmuc = [
            'loai_giay' => $loaiGiayPhep,
            'doi_tuong' => $doiTuong,
            'loai_cap' => $loaiCap,
            'muc_dich' => $mucDich
        ];
        $files = [];
        $fileIds = $data['files'] ? json_decode($data['files']) : [];
        if ($data['files'] && count($fileIds) > 0) {
            $files = DB::table('media')->whereIn('id', $fileIds)->select('id', 'name', 'disk', 'size')->get();
            $data['fileList'] = $files->toArray();
        } else $data['fileList'] = [];

        return view('admin.resources.giayphep.edit', ['danhmucs' => json_encode($danhmuc)], ['data' => json_encode($data)]);
    }
    public function xoaGiayPhep(Request $request, $id)
    {
        try {
            GiayPhepDaDangSinhHoc::find($id)->delete();
            $user = Auth::user();
            createLog($user->id, 'delete_giay_phep', $request->ip(), $request->header('User-Agent'), 'Xóa giấy phép');
            return response(['message' => 'Done'], 200);
        } catch (\Exception $e) {
            return response(['message' => 'Error'], 501);
        }
    }
    public function viewSearch()
    {
        $giayPhep =  Lookup::where('group', 'loaiGiayPhepDaDangSinhHoc')->get()->toArray();
        $loaiCap = Lookup::where('group', 'LoaiGiayPhep')->select('name', 'id')->get()->toArray();
        $danhmuc = [
            'loai_giay' => $giayPhep,
            'loai_cap' => $loaiCap
        ];
        return view('search.nolucbaoton.giayphep.permit', [
            'danhmucs' => json_encode($danhmuc),
            'lang' => Session::get('locale'),
        ]);
    }

    public function viewDetailSearch($id){
        $data = GiayPhepDaDangSinhHoc::where('id',$id)->with(['loaiGiayPhep', 'loaiCap', 'doiTuong', 'mucDich'])->first();
        $files = [];
        $fileIds = json_decode($data['files']);
        if ($data['files'] && count($fileIds) > 0) {
            $files = DB::table('media')->whereIn('id', $fileIds)->select('id', 'name', 'disk', 'size')->get();
            $data['fileList'] = $files->toArray();
        }else $data['fileList'] = [];
        return view('search.nolucbaoton.giayphep.detail', [
            'lang' => Session::get('locale'),
            'data' => json_encode($data)
        ]);
    }

    public function addGiayPhepv2(Request $request)
    {
        $info = $request->only(
            'ten',
            'ten_khoa_hoc',
            'mau_nguon_gen',
            'giay_phep_id',
            'so_hieu',
            'co_quan_cap',
            'ngay_cap',
            'files',
            'ngay_het_han',
            'ngay_hieu_luc',
            'loai_cap_phep_id',
            'don_vi_duoc_cap',
            'doi_tuong_id',
            'dia_chi',
            'chung_loai',
            'so_luong',
            'khoi_luong',
            'muc_dich_id',
            'ghi_chu',
            'don_vi_cung_cap',
            'dac_diem',
            'cach_tiep_can',
            'su_kien_chuyen_gen',
            'tinh_trang_lien_quan',
            'ma',
            'dac_diem_khai_thac',
            'phuong_tien_khai_thac',
            'hinh_thuc_khai_thac'
        );
        $validator = Validator::make($info, [
            'giay_phep_id' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'message' => __('Lỗi dữ liệu'),
            ], 401, []);
        }
        if (isset($info['files']) && is_array($info['files'])) {
            $info['files'] = json_encode($info['files']);
        } else {
            $info['files'] = null;
        }
        try {
            GiayPhepDaDangSinhHoc::create($info);
            return response(['message' => 'Done'], 200);
        } catch (\Exception $e) {
            return response(['message' => 'Không thể thêm mới'], 501);
        }
    }
    public function editGiayPhepv2(Request $request)
    {
        $info = $request->only(
            'id',
            'ten',
            'ten_khoa_hoc',
            'mau_nguon_gen',
            'giay_phep_id',
            'so_hieu',
            'co_quan_cap',
            'ngay_cap',
            'files',
            'ngay_het_han',
            'ngay_hieu_luc',
            'loai_cap_phep_id',
            'don_vi_duoc_cap',
            'doi_tuong_id',
            'dia_chi',
            'chung_loai',
            'so_luong',
            'khoi_luong',
            'muc_dich_id',
            'ghi_chu',
            'don_vi_cung_cap',
            'dac_diem',
            'cach_tiep_can',
            'su_kien_chuyen_gen',
            'tinh_trang_lien_quan',
            'ma',
            'dac_diem_khai_thac',
            'phuong_tien_khai_thac',
            'hinh_thuc_khai_thac'
        );
        $validator = Validator::make($info, [
            'id' => 'required',
            'giay_phep_id' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'message' => __('Lỗi dữ liệu'),
            ], 401, []);
        }
        try {
            if ($info['files']) {
                $info['files'] = json_encode($info['files']);
            }else $info['files'] = null;
            GiayPhepDaDangSinhHoc::find($info['id'])->update($info);
            return response(['message' => 'Done'], 200);
        } catch (\Exception $e) {
            dd($e);
            return response(['message' => 'Không thể thêm mới'], 501);
        }
    }

    public function getGiayPhepSearch(Request $request)
    {
        $page = $request->get('page', 1);
        $per_pager = $request->get('perPage', 5);
        $search = $request->get('search', null);
        $loai_giay = $request->get('loai_giay', null);
        $loai_cap = $request->get('loai_cap', null);
        $nam_cap_tu = $request->get('nam_cap_tu', null);
        $nam_cap_den = $request->get('nam_cap_den', null);
        $query = GiayPhepDaDangSinhHoc::with(['loaiGiayPhep', 'loaiCap', 'doiTuong']);
        if ($search != null) {
            $search = trim($search);
            $query = $query->where(function ($query) use ($search) {
                $query->where('co_quan_cap', 'ilike', "%{$search}%")
                    ->orWhere('don_vi_duoc_cap', 'ilike', "%{$search}%")
                    ->orWhere('ghi_chu', 'ilike', "%{$search}%");
            });
        }
        if ($nam_cap_tu != null) {
            $query->whereYear('ngay_cap', '>=', $nam_cap_tu);
        }
        if ($nam_cap_den != null) {
            $query->whereYear('ngay_cap', '<=', $nam_cap_den);
        }
        if ($loai_giay != null) {
            $query->whereIn('giay_phep_id', $loai_giay);
        }
        if ($loai_cap != null) {
            $query->whereIn('loai_cap_phep_id', $loai_cap);
        }

        $query->orderBy('updated_at', 'DESC');
        $query->orderBy('id', 'DESC');
        $data =  $query->paginate($per_pager, ['*'], 'page', $page);
        foreach ($data as $item) {
            $files = [];
            $fileIds = json_decode($item['files']);
            if ($item['files'] && count($fileIds) > 0) {
                $files = DB::table('media')->whereIn('id', $fileIds)->select('id', 'name', 'disk', 'size')->get();
                $item['fileList'] = $files->toArray();
            } else $item['fileList'] = [];
        }
        return $data;
    }
}
