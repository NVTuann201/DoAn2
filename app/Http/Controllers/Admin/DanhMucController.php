<?php

namespace App\Http\Controllers\Admin;

use App\BangTinTongHop;
use App\BaoCaoApLuc;
use App\ChiThi;
use App\District;
use App\Http\Controllers\Controller;
use App\Lookup;
use App\NhomChiThi;
use App\ProtectedArea;
use App\ThongSo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class DanhMucController extends Controller
{
    public function chiThiView()
    {
        return view('admin.category.chithi.index');
    }
    public function thongSoView($id)
    {
        $chiThi = ChiThi::where('id', $id)->with('nhomChiThi')->first();
        $soThongSo = ThongSo::where('chi_thi_id', $id)->count();
        $chiThi['so_thong_so'] = $soThongSo;
        return view('admin.category.thongso.index', ['chiThi' => $chiThi]);
    }

    public function addNhomChiThi(Request $request)
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

            NhomChiThi::create($info);
            return response(['message' => 'Done'], 200);
        } catch (\Exception $e) {
            return response(['message' => 'Không thể thêm nhóm chỉ thị'], 501);
        }
    }
    public function xoaNhomChiThi($id)
    {
        try {
            $nhomChiThi = NhomChiThi::where('id', $id)->first();
            $check =  ChiThi::where('nhom_chi_thi_id', $id)->count();
            if ($check > 0) {
                return response(['message' => 'Không thể xóa dữ liệu'], 502);
            }
            $nhomChiThi->delete();
            return response(['message' => 'Done'], 200);
        } catch (\Exception $e) {
            return $e;
            return response(['Không thể xóa dữ liệu'], 501);
        }
    }
    public function xoaChiThi($id)
    {
        try {
            BangTinTongHop::truncate();
            ThongSo::where('chi_thi_id', $id)->delete();
            ChiThi::where('id', $id)->delete();
            return response(['message' => 'Done'], 200);
        } catch (\Exception $e) {
            dd($e);
            return response(['Không thể xóa dữ liệu'], 501);
        }
    }
    public function getNhomChiThi()
    {
        $data =  NhomChiThi::orderBy('updated_at', 'DESC')->get();
        foreach ($data as $item) {
            $soChiThi = ChiThi::where('nhom_chi_thi_id', $item['id'])->count();
            $item['so_chi_thi'] = $soChiThi;
        }
        return $data;
    }

    public function editNhomChiThi(Request $request)
    {
        $info = $request->only('id', 'ten', 'mo_ta');
        $validator = Validator::make($info, [
            'id' => 'required',
            'ten' => 'required|max:255',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'message' => __('Lỗi dữ liệu'),
            ], 401, []);
        }
        try {
            NhomChiThi::where('id', $info['id'])->first()->update($info);
            return response(['message' => 'Done'], 200);
        } catch (\Exception $e) {
            return response(['message' => 'Không thể thêm nhóm chỉ thị'], 501);
        }
    }
    public function addChiThi(Request $request)
    {
        $info = $request->only('ten', 'mo_ta');
        $validator = Validator::make($info, [
            'ten' => 'required|max:255|unique:chi_this',
        ],[
            'ten.unique'=>'Email đã tồn tại', 
        ]);
        if ($validator->fails()) {
            return response()->json([
                'message' => __('message.vaidator'),
                'error' => $validator->errors()
            ], 200, []);
        }
        try {

            ChiThi::create($info);
            return response(['message' => 'Done'], 200);
        } catch (\Exception $e) {
            return response(['message' => 'Không thể thêm chỉ thị'], 501);
        }
    }

    public function getChiThi(Request $request)
    {
        $page = $request->get('page', 1);
        $per_pager = $request->get('perPage', 5);
        $search = $request->get('search', null);
        $query = ChiThi::query();
        if ($search != null) {
            $search = trim($search);
            $query = $query->where(function ($query) use ($search) {
                $query->where('ten', 'ilike', "%{$search}%")
                    ->orWhere('mo_ta', 'ilike', "%{$search}%");
            });
        }
        $query->orderBy('updated_at', 'DESC');
        $query->orderBy('id', 'DESC');
        $data =  $query->paginate($per_pager, ['*'], 'page', $page);
        foreach ($data as $item) {
            $soThongSo = ThongSo::where('chi_thi_id', $item['id'])->count();
            $item['so_thong_so'] =  $soThongSo;
        }
        return $data;
    }

    public function editChiThi(Request $request)
    {
        $info = $request->only('id', 'ten', 'mo_ta');
        $validator = Validator::make($info, [
            'id' => 'required',
            'ten' => 'required|max:255'
        ]);
        if ($validator->fails()) {
            return response()->json([
                'message' => __('Lỗi dữ liệu'),
            ], 200, []);
        }
        $trunglap=ChiThi::where('ten', $info['ten'])->where('id', '!=', $info['id'])->first();
        if($trunglap){
            return response()->json([
                'message' => __('Trùng lặp tên chỉ thị'),
            ], 200, []);
        }
        try {
            ChiThi::where('id', $info['id'])->first()->update($info);
            return response(['message' => 'Done'], 200);
        } catch (\Exception $e) {
            return response(['message' => 'Không cập nhật chỉ thị'], 501);
        }
    }
    public function getThongSo(Request $request)
    {
        $chiThiId = $request->get('chi_thi_id', null);
        if ($chiThiId == null) {
            return [];
        }
        $page = $request->get('page', 1);
        $per_pager = $request->get('perPage', 5);
        $search = $request->get('search', null);
        $query = ThongSo::where('chi_thi_id', $chiThiId);
        if ($search != null) {
            $search = trim($search);
            $query = $query->where(function ($query) use ($search) {
                $query->where('ten', 'ilike', "%{$search}%")
                    ->orWhere('mo_ta', 'ilike', "%{$search}%");
            });
        }
        $query->orderBy('updated_at', 'DESC');
        $query->orderBy('id', 'DESC');
        $data =  $query->paginate($per_pager, ['*'], 'page', $page);
        return $data;
    }
    public function addThongSo(Request $request)
    {
        $info = $request->only('chi_thi_id', 'ten', 'mo_ta');
        $validator = Validator::make($info, [
            'ten' => 'required|max:255',
            'chi_thi_id' => 'required'
        ]);
        if ($validator->fails()) {
            return response()->json([
                'message' => __('Lỗi dữ liệu'),
            ], 200, []);
        }
        try {

            ThongSo::create($info);
            return response(['message' => 'Done'], 200);
        } catch (\Exception $e) {
            return response(['message' => 'Không thể thêm thông số'], 501);
        }
    }
    public function editThongSo(Request $request)
    {
        $info = $request->only('chi_thi_id', 'ten', 'mo_ta', 'id');
        $validator = Validator::make($info, [
            'ten' => 'required|max:255',
            'chi_thi_id' => 'required',
            'id' => 'required'
        ]);
        if ($validator->fails()) {
            return response()->json([
                'message' => __('Lỗi dữ liệu'),
            ], 200, []);
        }
        try {

            ThongSo::where('id', $info['id'])->first()->update($info);
            return response(['message' => 'Done'], 200);
        } catch (\Exception $e) {
            return response(['message' => 'Không thể cập nhật thông số'], 501);
        }
    }

    public function xoaThongSo($id)
    {
        try {
            ThongSo::find($id)->delete();
            return response(['message' => 'Done'], 200);
        } catch (\Exception $e) {
            return response(['message' => 'Error'], 500);
        }
    }
    public function viewBangTin()
    {
        $thongSo = ThongSo::get();
        $phanCap = Lookup::where('group', 'phanCapBangTinTongHop')->get();
        $danhmucs = [
            'thong_so' => $thongSo,
            'phan_cap' => $phanCap
        ];
        return view('admin.category.bangtintonghop.index', ['danhmucs' => json_encode($danhmucs)]);
    }

    public function addBangTin(Request $request)
    {
        $info = $request->only(
            'thong_so_id',
            'chi_so',
            'gia_tri',
            'don_vi_tinh',
            'phan_cap_id',
            'don_vi_bao_cao',
            'ghi_chu',
            'nguon_du_lieu',
            'ky_bao_cao',
            'bat_dau',
            'ket_thuc',
            'mo_ta',
            'files'
        );
        $validator = Validator::make($info, [
            'thong_so_id' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'message' => __('Lỗi dữ liệu'),
            ], 200, []);
        }
        try {
            if (isset($info['files']) && is_array($info['files'])) {
                $info['files'] = json_encode($info['files']);
            } else {
                $info['files'] = null;
            }
            BangTinTongHop::create($info);
            return response(['message' => 'Done'], 200);
        } catch (\Exception $e) {
            return response(['message' => 'Không thể thêm thông số'], 501);
        }
    }

    public function editBangTin(Request $request)
    {
        $info = $request->only(
            'id',
            'thong_so_id',
            'chi_so',
            'gia_tri',
            'don_vi_tinh',
            'phan_cap_id',
            'don_vi_bao_cao',
            'ghi_chu',
            'nguon_du_lieu',
            'ky_bao_cao',
            'bat_dau',
            'ket_thuc',
            'mo_ta',
            'files'
        );
        $validator = Validator::make($info, [
            'thong_so_id' => 'required',
            'id' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'message' => __('Lỗi dữ liệu'),
            ], 200, []);
        }
        try {
            if (isset($info['files']) && is_array($info['files'])) {
                $info['files'] = json_encode($info['files']);
            } else {
                $info['files'] = null;
            }
            BangTinTongHop::find($info['id'])->update($info);
            return response(['message' => 'Done'], 200);
        } catch (\Exception $e) {
            return response(['message' => 'Không thể thêm thông số'], 501);
        }
    }

    public function getBangTin(Request $request)
    {
        $page = $request->get('page', 1);
        $per_pager = $request->get('perPage', 5);
        $search = $request->get('search', null);
        $phan_cap_id = $request->get('phan_cap_id', null);
        $query = BangTinTongHop::with(['phanCap', 'thongSo']);
        if ($search != null) {
            $search = trim($search);
            $query = $query->where(function ($query) use ($search) {
                $query->where('don_vi_bao_cao', 'ilike', "%{$search}%")
                    ->orWhere('ghi_chu', 'ilike', "%{$search}%")
                    ->orWhere('nguon_du_lieu', 'ilike', "%{$search}%")
                    ->orWhere('ky_bao_cao', 'ilike', "%{$search}%")
                    ->orWhere('mo_ta', 'ilike', "%{$search}%")
                    ->orWhereHas('thongSo', function($query) use ($search){
                        $query->where('ten', 'ilike', "%{$search}%");
                    });
            });
        }
        if ($phan_cap_id) {
            $query = $query->where('phan_cap_id', $phan_cap_id);
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

    public function getChiSoApluc()
    {
        $phanCap = Lookup::where('group', 'phanCapBangTinTongHop')->select('id', 'name', 'code')->get();
        foreach ($phanCap as $item) {
            $item['so_luong'] = BaoCaoApLuc::where('phan_cap_id', $item['id'])->count();
        }

        return response(['phan_cap' =>  $phanCap], 200);
    }

    public function viewApLuc()
    {
        $phanCap = Lookup::where('group', 'phanCapBangTinTongHop')->get();
        $khuBaoTon = ProtectedArea::select('id', 'name', 'orig_name', 'name', 'desig')->get();
        $chiThi = ChiThi::get();
        $danhmucs = [
            'phan_cap' => $phanCap,
            'chi_thi' => $chiThi,
            'khu_bao_ton' => $khuBaoTon
        ];
        return view('admin.resources.baocaoapluc.index', ['danhmucs' => json_encode($danhmucs)]);
    }

    public function addBaoCaoApLuc(Request $request)
    {
        $info = $request->only(
            'chi_thi_id',
            'gia_tri',
            'don_vi_tinh',
            'phan_cap_id',
            'don_vi_bao_cao',
            'nguon_du_lieu',
            'ky_bao_cao',
            'loai_doi_tuong',
            'doi_tuong_id',
            'bat_dau',
            'ket_thuc',
            'mo_ta',
            'files'
        );
        $validator = Validator::make($info, [
            'chi_thi_id' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'message' => __('Lỗi dữ liệu'),
            ], 200, []);
        }
        $trungLap=BaoCaoApLuc::where('chi_thi_id', $info['chi_thi_id'])->where('phan_cap_id', $info['phan_cap_id'])->first();
        if($trungLap){
            return response()->json([
                'message' => __('Trùng lặp dữ liệu'),
            ], 200, []);
        }
        if (isset($info['bat_dau']) && isset($info['ket_thuc']) && $info['bat_dau'] && $info['ket_thuc'] && strtotime($info['bat_dau']) > strtotime($info['ket_thuc'])){
            return response()->json([
                'message' => __('datetime'),
            ], 200, []);
        }
        try {
            if (isset($info['files']) && is_array($info['files'])) {
                $info['files'] = json_encode($info['files']);
            } else {
                $info['files'] = null;
            }
            BaoCaoApLuc::create($info);
            $user = Auth::user();
            createLog($user->id,'add_bao_cao_ap_luc',$request->ip(),$request->header('User-Agent'),'Thêm mới báo cáo áp lực');
            return response(['message' => 'Done'], 200);
        } catch (\Exception $e) {
            return response(['message' => 'Không thể thêm thông số'], 501);
        }
    }

    public function editBaoCaoApLuc(Request $request)
    {
        $info = $request->only(
            'id',
            'chi_thi_id',
            'gia_tri',
            'don_vi_tinh',
            'phan_cap_id',
            'don_vi_bao_cao',
            'nguon_du_lieu',
            'ky_bao_cao',
            'bat_dau',
            'ket_thuc',
            'mo_ta',
            'loai_doi_tuong',
            'doi_tuong_id',
            'files'
        );
        $validator = Validator::make($info, [
            'chi_thi_id' => 'required',
            'id' => 'required'
        ]);
        if ($validator->fails()) {
            return response()->json([
                'message' => __('Lỗi dữ liệu'),
            ], 200, []);
        }
        $trungLap=BaoCaoApLuc::where('chi_thi_id', $info['chi_thi_id'])->where('phan_cap_id', $info['phan_cap_id'])->where('id','!=', $info['id'] )->first();
        if($trungLap){
            return response()->json([
                'message' => __('Trùng lặp dữ liệu'),
            ], 200, []);
        }
        if (isset($info['bat_dau']) && isset($info['ket_thuc']) && $info['bat_dau'] && $info['ket_thuc'] && strtotime($info['bat_dau']) > strtotime($info['ket_thuc'])){
            return response()->json([
                'message' => __('datetime'),
            ], 200, []);
        }
        try {
            if (isset($info['files']) && is_array($info['files'])) {
                $info['files'] = json_encode($info['files']);
            } else {
                $info['files'] = null;
            }
            BaoCaoApLuc::find($info['id'])->update($info);
            $user = Auth::user();
            createLog($user->id,'update_bao_cao_ap_luc',$request->ip(),$request->header('User-Agent'),'Cập nhật báo cáo áp lực');
            return response(['message' => 'Done'], 200);
        } catch (\Exception $e) {
            return response(['message' => 'Không thể thêm thông số'], 501);
        }
    }

    public function getBaoCaoApLuc(Request $request){
        $page = $request->get('page', 1);
        $per_pager = $request->get('perPage', 5);
        $search = $request->get('search', null);
        $phan_cap_id = $request->get('phan_cap_id', null);
        $khu_bao_ton_id = $request->get('khu_bao_ton_id', null);
        $query = BaoCaoApLuc::with(['phanCap', 'chiThi']);
        if ($search != null) {
            $search = trim($search);
            $query = $query->where(function ($query) use ($search) {
                $query->where('don_vi_bao_cao', 'ilike', "%{$search}%")
                    // ->orWhere('ghi_chu', 'ilike', "%{$search}%")
                    ->orWhere('nguon_du_lieu', 'ilike', "%{$search}%")
                    ->orWhere('ky_bao_cao', 'ilike', "%{$search}%")
                    ->orWhere('mo_ta', 'ilike', "%{$search}%")
                    ->orWhereHas('chiThi', function($query) use ($search){
                        $query->where('ten', 'ilike', "%{$search}%");
                    });
            });
        }
        if ($phan_cap_id) {
            $query = $query->where('phan_cap_id', $phan_cap_id);
        }
        if($khu_bao_ton_id){
            $query = $query->where('loai_doi_tuong', 'khu_bao_ton')->where('doi_tuong_id', $khu_bao_ton_id);
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

    public function xoaBaoCaoApluc(Request $request, $id){
        try{
            BaoCaoApLuc::find($id)->delete();
            $user = Auth::user();
            createLog($user->id,'delete_bao_cao_ap_luc',$request->ip(),$request->header('User-Agent'),'Xóa báo cáo áp lực');
            return response(['message' => 'Done'], 200);
        }catch(\Exception $e){
            dd($e);
        }
    }

    public function viewDiaGioi(){
        return view('admin.category.diagioi.index');
    }

    public function getQuanHuyen(Request $request){
        $page = $request->get('page', 1);
        $per_pager = $request->get('perPage', 10);
        $search = $request->get('search', null);
        $query = District::where('province_id', 26);
        if ($search != null) {
            $search = trim($search);
            $query = $query->where(function ($query) use ($search) {
                $query->where('name', 'ilike', "%{$search}%")
                    ->orWhere('name_vietnamese', 'ilike', "%{$search}%");
            });
        }
        $data =  $query->paginate($per_pager, ['*'], 'page', $page);
        return $data;
    }
}
