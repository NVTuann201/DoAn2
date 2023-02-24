<?php

namespace App\Http\Controllers\admin;

use App\District;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Lookup;
use App\MoHinhSangKien;
use App\Province;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

use function GuzzleHttp\json_encode;

class MoHinhSangKienController extends Controller
{
    public function viewIndex()
    {
        $hinhThucs = Lookup::where('group', 'hinhthucsangkien')->get();
        $quanHuyen = District::select('name', 'id', 'name_vietnamese')->get();
        $phancaps =  Lookup::where('group', 'phanCap')->get();
        return view('admin.resources.mohinhsangkien.index', ['hinhthucs' => json_encode($hinhThucs), 'quanhuyens' => json_encode($quanHuyen), 'phancaps' => json_encode($phancaps)]);
    }

    public function addMoHinhSangKien(Request $request)
    {
        $info = $request->only('ten', 'hinh_thuc_id', 'co_quan_to_chuc', 'nam_thuc_hien', 'phan_loai_id', 'dia_diem_to_chuc', 'ket_qua_ap_dung', 'quan_huyen_id', 'phan_cap_id', 'noi_dung', 'ghi_chu', 'files');
        $validator = Validator::make($info, [
            'ten' => 'required|unique:mo_hinh_sang_kiens',
        ], [
            'ten.unique'=>'Tên mô hình đã tồn tại', 
        ]);
        if ($validator->fails()) {
            return response()->json([
                'message' => __('message.vaidator'),
                'error' => $validator->errors()
            ], 200, []);
        }
        if (isset($info['files']) && is_array($info['files'])) {
            $info['files'] = json_encode($info['files']);
        } else {
            $info['files'] = null;
        }
        try {
            MoHinhSangKien::create($info);
            $user = Auth::user();
            createLog($user->id, 'add_mo_hinh_sang_kien', $request->ip(), $request->header('User-Agent'), 'Thêm mới mô hình sáng kiến ' .$info['ten']);
            return response(['message' => 'Done'], 200);
        } catch (\Exception $e) {
            return response(['message' => 'Không thể thêm mới'], 501);
        }
    }
    public function editMoHinhSangKien(Request $request)
    {
        $info = $request->only('id', 'ten', 'hinh_thuc_id', 'co_quan_to_chuc', 'nam_thuc_hien', 'phan_loai_id', 'dia_diem_to_chuc', 'ket_qua_ap_dung', 'quan_huyen_id', 'phan_cap_id', 'noi_dung', 'ghi_chu', 'files');
        $validator = Validator::make($info, [
            'ten' => 'required',
            'id' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'message' => __('Lỗi dữ liệu'),
            ], 401, []);
        }
        $trungLap=MoHinhSangKien::where('ten', $info['ten'])->where('id','!=', $info['id'] )->first();
        if($trungLap){
            return response()->json([
                'message' => __('Tên mô hình đã tồn tại'),
            ], 200, []);
        }
        if (isset($info['files']) && is_array($info['files'])) {
            $info['files'] = json_encode($info['files']);
        } else {
            $info['files'] = null;
        }
        try {
            MoHinhSangKien::find($info['id'])->update($info);
            $user = Auth::user();
            createLog($user->id, 'update_mo_hinh_sang_kien', $request->ip(), $request->header('User-Agent'), 'Cập nhật mô hình sáng kiến '.$info['ten']);
            return response(['message' => 'Done'], 200);
        } catch (\Exception $e) {
            dd($e);
            return response(['message' => 'Không thể thêm mới'], 501);
        }
    }
    public function getMoHinhSangKien(Request $request)
    {
        $page = $request->get('page', 1);
        $per_pager = $request->get('perPage', 5);
        $search = $request->get('search', null);
        $nam_thuc_hien = $request->get('nam_thuc_hien', null);
        $phan_loai = $request->get('phan_loai_id', null);
        $hinh_thuc = $request->get('hinh_thuc_id', null);
        $query = MoHinhSangKien::with(['phanLoai', 'hinhThuc']);
        if ($search != null) {
            $search = trim($search);
            $query = $query->where(function ($query) use ($search) {
                $query->where('ten', 'ilike', "%{$search}%")
                    ->orWhere('co_quan_to_chuc', 'ilike', "%{$search}%")
                    ->orWhere('dia_diem_to_chuc', 'ilike', "%{$search}%")
                    ->orWhere('ket_qua_ap_dung', 'ilike', "%{$search}%")
                    ->orWhere('noi_dung', 'ilike', "%{$search}%")
                    ->orWhere('ghi_chu', 'ilike', "%{$search}%");
            });
        }
        if ($nam_thuc_hien != null) {
            $query->whereYear('nam_thuc_hien', $nam_thuc_hien);
        }
        if ($phan_loai != null) {
            $query->where('phan_loai_id', $phan_loai);
        }
        if ($hinh_thuc != null) {
            $query->where('hinh_thuc_id', $hinh_thuc);
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
    public function getChiSo()
    {
        $hinhThuc =  Lookup::where('group', 'hinhthucsangkien')->select('id', 'name')->get();
        $phancaps =  Lookup::where('group', 'phanCap')->select('id', 'name')->get();
        foreach ($hinhThuc as $item) {
            $item['so_luong'] = MoHinhSangKien::where('hinh_thuc_id', $item['id'])->count();
        }
        foreach ($phancaps as $item) {
            $item['so_luong'] = MoHinhSangKien::where('phan_loai_id', $item['id'])->count();
        }
        return response(['hinh_thuc' => $hinhThuc, 'phan_loai' => $phancaps], 200);
    }

    public function xoaMoHinh(Request $request, $id){
        try{
            MoHinhSangKien::find($id)->delete();
            $user = Auth::user();
            createLog($user->id, 'delete_mo_hinh_sang_kien', $request->ip(), $request->header('User-Agent'), 'Xóa mô hình sáng kiến');
            return response(['message' => 'Done'], 200);
        }catch(\Exception $e){
            return response(['message' => 'Error'], 500);
        }
    }
    public function viewSearch()
    {
        return view('search.nolucbaoton.initiative', [
            'lang' => Session::get('locale'),
        ]);
    }
}
