<?php

namespace App\Http\Controllers\admin;

use App\District;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\KiemSoatSinhVatNgoaiLai;
use App\Province;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class KiemSoatSinhVatNgoaiLaiController extends Controller
{
    public function viewIndex()
    {

        $quanHuyen = District::select('name', 'id', 'name_vietnamese')->get();
        return view('admin.resources.sinhvatngoailai.index', ['quanhuyens' => $quanHuyen]);
    }

    public function addDeTai(Request $request)
    {
        $info = $request->only('ten', 'ma_so','thoi_gian_bat_dau', 'thoi_gian_ket_thuc', 'don_vi_phoi_hop', 'don_vi_chu_tri', 'thuoc_chuong_trinh', 'quan_huyen_id', 'noi_dung', 'files');
        $validator = Validator::make($info, [
            'ten' => 'required',
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
            KiemSoatSinhVatNgoaiLai::create($info);
            $user = Auth::user();
            createLog($user->id, 'add_sinh_vat_ngoai_lai', $request->ip(), $request->header('User-Agent'), 'Thêm mới sinh vật ngoại lai');
            return response(['message' => 'Done'], 200);
        } catch (\Exception $e) {
            dd($e);
            return response(['message' => 'Không thể thêm mới'], 501);
        }
    }
    public function editDeTai(Request $request)
    {
        $info = $request->only('id', 'ten', 'ma_so','thoi_gian_bat_dau', 'thoi_gian_ket_thuc', 'don_vi_phoi_hop', 'don_vi_chu_tri', 'thuoc_chuong_trinh', 'quan_huyen_id', 'noi_dung', 'files');
        $validator = Validator::make($info, [
            'ten' => 'required',
            'id' => 'required',
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
            KiemSoatSinhVatNgoaiLai::find($info['id'])->update($info);
            $user = Auth::user();
            createLog($user->id, 'update_sinh_vat_ngoai_lai', $request->ip(), $request->header('User-Agent'), 'Cập nhật sinh vật ngoại lai');
            return response(['message' => 'Done'], 200);
        } catch (\Exception $e) {
            dd($e);
            return response(['message' => 'Không thể thêm mới'], 501);
        }
    }
    public function getDeTai(Request $request)
    {
        $page = $request->get('page', 1);
        $per_pager = $request->get('perPage', 5);
        $search = $request->get('search', null);
        $bat_dau = $request->get('bat_dau', null);
        $ket_thuc = $request->get('ket_thuc', null);
        $query = KiemSoatSinhVatNgoaiLai::query();
        if ($search != null) {
            $search = trim($search);
            $query = $query->where(function ($query) use ($search) {
                $query->where('ten', 'ilike', "%{$search}%")
                    ->orWhere('don_vi_chu_tri', 'ilike', "%{$search}%")
                    ->orWhere('noi_dung', 'ilike', "%{$search}%");
            });
        }
        if ($bat_dau != null) {
            $query->whereYear('thoi_gian_bat_dau', $bat_dau);
        }
        if ($ket_thuc != null) {
            $query->whereYear('thoi_gian_ket_thuc', $ket_thuc);
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

    public function xoaDeTai(Request $request, $id){
        try{
            KiemSoatSinhVatNgoaiLai::find($id)->delete();
            $user = Auth::user();
            createLog($user->id, 'delete_sinh_vat_ngoai_lai', $request->ip(), $request->header('User-Agent'), 'Xóa sinh vật ngoại lai');
            return response(['message' => 'Done'], 200);
        }catch(\Exception $e){
            return response(['message' => 'Error'], 500);
        }
    }

    public function viewSearch()
    {
        return view('search.nolucbaoton.alien', [
            'lang' => Session::get('locale'),
        ]);
    }
}
