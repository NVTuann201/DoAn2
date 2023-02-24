<?php

namespace App\Http\Controllers\Admin;

use App\District;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\KinhPhiHangNam;
use App\Lookup;
use App\Province;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class NguonKinhPhiController extends Controller
{
    public function viewSearch()
    {
        return view('search.nolucbaoton.budget', [
            'lang' => Session::get('locale'),
        ]);
    }

    public function viewIndex(){
        $quanHuyen = District::select('name', 'id', 'name_vietnamese')->get();
        $nguonKinhPhi = Lookup::where('group', 'nguonKinhPhiHangNam')->get();
        $danhmuc = [
            'quan_huyen' => $quanHuyen,
            'nguon_kinh_phi' => $nguonKinhPhi
        ];
        return view('admin.resources.kinhphi.index', ['danhmucs' => json_encode($danhmuc)]);
    }

    public function addKinhPhi(Request $request){
        $info = $request->only('nguon_kinh_phi_id', 'tong_kinh_phi', 'muc_dich_su_dung', 'ty_le_ngan_sach', 'thoi_gian', 'quan_huyen_id', 'ghi_chu');
        $validator = Validator::make($info, [
            'nguon_kinh_phi_id' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'message' => __('Lỗi dữ liệu'),
            ], 401, []);
        }
        try {
            KinhPhiHangNam::create($info);
            $user = Auth::user();
            createLog($user->id, 'add_nguon_kinh_phi', $request->ip(), $request->header('User-Agent'), 'Thêm mới nguồn kinh tế');
            return response(['message' => 'Done'], 200);
        } catch (\Exception $e) {
            return response(['message' => 'Không thể thêm mới'], 501);
        }
    }

    public function getKinhPhi(Request $request){
        $page = $request->get('page', 1);
        $per_pager = $request->get('perPage', 5);
        $search = $request->get('search', null);
        $nguon_kinh_phi_id = $request->get('nguon_kinh_phi_id', null);
        $query = KinhPhiHangNam::with(['nguonKinhPhi', 'quanHuyen']);
        if ($search != null) {
            $search = trim($search);
            $query = $query->where(function ($query) use ($search) {
                $query->where('muc_dich_su_dung', 'ilike', "%{$search}%")
                    ->orWhere('ghi_chu', 'ilike', "%{$search}%");
            });
        }
        if($nguon_kinh_phi_id){
            $query->where('nguon_kinh_phi_id', $nguon_kinh_phi_id);
        }
        $query->orderBy('updated_at', 'DESC');
        $query->orderBy('id', 'DESC');
        $data =  $query->paginate($per_pager, ['*'], 'page', $page);
        return $data;
    }

    public function updateKinhPhi(Request $request){
        $info = $request->only('id','nguon_kinh_phi_id', 'tong_kinh_phi', 'muc_dich_su_dung', 'ty_le_ngan_sach', 'thoi_gian', 'quan_huyen_id', 'ghi_chu');
        $validator = Validator::make($info, [
            'nguon_kinh_phi_id' => 'required',
            'id' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'message' => __('Lỗi dữ liệu'),
            ], 401, []);
        }
        try {
            KinhPhiHangNam::find($info['id'])->update($info);
            $user = Auth::user();
            createLog($user->id, 'update_sinh_vat_ngoai_lai', $request->ip(), $request->header('User-Agent'), 'Cập nhật nguồn kinh phí');
            return response(['message' => 'Done'], 200);
        } catch (\Exception $e) {
            return response(['message' => 'Không thể thêm mới'], 501);
        }
    }

    public function xoaKinhPhi(Request $request, $id){
        try{
            KinhPhiHangNam::find($id)->delete();
            $user = Auth::user();
            createLog($user->id, 'update_sinh_vat_ngoai_lai', $request->ip(), $request->header('User-Agent'), 'Xóa nguồn kinh phí');
            return response(['message' => 'Done'], 200);
        }catch(\Exception $e){
            return response(['message' => 'Error'], 500);
        }
    }
    public function getChiSo()
    {
        $nguons =  Lookup::where('group', 'nguonKinhPhiHangNam')->select('id', 'name')->get();
        foreach ($nguons as $item) {
            $item['so_luong'] = KinhPhiHangNam::where('nguon_kinh_phi_id', $item['id'])->count();
        }
        return response(['nguon_kinh_phi' => $nguons], 200);
    }
}
