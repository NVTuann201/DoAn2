<?php

namespace App\Http\Controllers\admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Lookup;
use App\VanBanPhapLuat;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

use function GuzzleHttp\json_encode;

class VanBanPhapLuatController extends Controller
{
    public function viewIndex()
    {
        $phanLoais = Lookup::where('group', 'LoaiVanBanPhapLuat')->get()->toArray();
        $banHanhs = Lookup::where('group', 'CoQuanBanHanhVanBanPhapLuat')->get()->toArray();
        $linhVucs = Lookup::where('group', 'LinhVucVanBanPhapLuat')->get()->toArray();
        $data = ['phan_loais' => $phanLoais, 'co_quans' => $banHanhs, 'linh_vucs' => $linhVucs];
        return view('admin.resources.vanbanphapluat.index',  ['danhmucs' => json_encode($data)]);
    }
    public function getDanhMuc()
    {
        $phanLoais = Lookup::where('group', 'LoaiVanBanPhapLuat')->get()->toArray();
        $banHanhs = Lookup::where('group', 'CoQuanBanHanhVanBanPhapLuat')->get()->toArray();
        $linhVucs = Lookup::where('group', 'LinhVucVanBanPhapLuat')->get()->toArray();
        $data = ['phan_loais' => $phanLoais, 'co_quans' => $banHanhs, 'linh_vucs' => $linhVucs];
        return response(['data' => $data], 200);
    }
    public function addVanBanPhapLuat(Request $request)
    {
        $info = $request->only('ten', 'loai_id', 'ngay_ban_hanh', 'ngay_hieu_luc', 'so_hieu', 'co_quan_ban_hanh_id', 'hieu_luc', 'linh_vuc_id', 'files', 'noi_dung_chinh', 'noi_dung_toan_van', 'ghi_chu');
        $validator = Validator::make($info, [
            'ten' => 'required|max:255|unique:van_ban_phap_luats',
            'loai_id' => 'required',
            'so_hieu' => 'required',
            'co_quan_ban_hanh_id' => 'required',
        ], [
            'ten.unique'=>'Tên văn bản đã tồn tại', 
        ]);
        if ($validator->fails()) {
            return response()->json([
                'message' => __('message.vaidator'),
                'error' => $validator->errors()
            ], 200, []);
        }
        if (isset($info['ngay_ban_hanh']) && isset($info['ngay_hieu_luc']) && $info['ngay_ban_hanh'] && $info['ngay_hieu_luc'] && strtotime($info['ngay_ban_hanh']) > strtotime($info['ngay_hieu_luc'])){
            return response()->json([
                'message' => __('datetime'),
            ], 200, []);
        }
        if (isset($info['files']) && is_array($info['files'])) {
            $info['files'] = json_encode($info['files']);
        } else {
            $info['files'] = null;
        }
        try {
            VanBanPhapLuat::create($info);
            $user = Auth::user();
            createLog($user->id, 'add_van_ban_phap_luat', $request->ip(), $request->header('User-Agent'), 'Thêm mới văn bản pháp luật '.$info['ten']);
            return response(['message' => 'Done'], 200);
        } catch (\Exception $e) {
            return response(['message' => 'Không thể thêm mới'], 501);
        }
    }
    public function editVanBanPhapLuat(Request $request)
    {
        $info = $request->only('id', 'ten', 'loai_id', 'ngay_ban_hanh', 'ngay_hieu_luc', 'so_hieu', 'co_quan_ban_hanh_id', 'hieu_luc', 'linh_vuc_id', 'files', 'noi_dung_chinh', 'noi_dung_toan_van', 'ghi_chu');
        $validator = Validator::make($info, [
            'id' => 'required',
            'ten' => 'required|max:255',
            'loai_id' => 'required',
            'so_hieu' => 'required',
            'co_quan_ban_hanh_id' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'message' => __('Lỗi dữ liệu'),
            ], 401, []);
        }
        $trungLap=VanBanPhapLuat::where('ten', $info['ten'])->where('id','!=', $info['id'] )->first();
        if($trungLap){
            return response()->json([
                'message' => __('Tên mô hình đã tồn tại'),
            ], 200, []);
        }
        if (isset($info['ngay_ban_hanh']) && isset($info['ngay_hieu_luc']) && $info['ngay_ban_hanh'] && $info['ngay_hieu_luc'] && strtotime($info['ngay_ban_hanh']) > strtotime($info['ngay_hieu_luc'])){
            return response()->json([
                'message' => __('datetime'),
            ], 200, []);
        }
        if (isset($info['files']) && is_array($info['files'])) {
            $info['files'] = json_encode($info['files']);
        } else {
            $info['files'] = null;
        }
        try {
            VanBanPhapLuat::find($info['id'])->update($info);
            $user = Auth::user();
            createLog($user->id, 'update_van_ban_phap_luat', $request->ip(), $request->header('User-Agent'), 'Cập nhật văn bản pháp luật '.$info['ten']);
            return response(['message' => 'Done'], 200);
        } catch (\Exception $e) {
            return response(['message' => 'Không thể thêm mới'], 501);
        }
    }

    public function getVanBanPhapLuat(Request $request)
    {
        $page = $request->get('page', 1);
        $per_pager = $request->get('perPage', 5);
        $search = $request->get('search', null);
        $nam_ban_hanh = $request->get('nam_ban_hanh', null);
        $nam_co_hieu_luc = $request->get('nam_co_hieu_luc', null);
        $hieu_luc = $request->get('hieu_luc', null);
        $co_quan_id = $request->get('co_quan_id', null);
        $loai_id = $request->get('loai_id', null);
        $linh_vuc_id = $request->get('linh_vuc_id', null);

        $query = VanBanPhapLuat::with(['phanLoai', 'linhVuc', 'coQuanBanHanh']);
        if ($search != null) {
            $search = trim($search);
            $query = $query->where(function ($query) use ($search) {
                $query->where('ten', 'ilike', "%{$search}%")
                    ->orWhere('noi_dung_chinh', 'ilike', "%{$search}%")
                    ->orWhere('noi_dung_toan_van', 'ilike', "%{$search}%")
                    ->orWhere('ghi_chu', 'ilike', "%{$search}%");
                // ->orWhereHas('phanLoai', function ($query) use ($search) {
                //     $query->where('name', 'ilike', "%{$search}%");
                // })
                // ->orWhereHas('linhVuc', function ($query) use ($search) {
                //     $query->where('name', 'ilike', "%{$search}%");
                // })
                // ->orWhereHas('coQuanBanHanh', function ($query) use ($search) {
                //     $query->where('name', 'ilike', "%{$search}%");
                // });
            });
        }
        if ($nam_ban_hanh != null) {
            $query->whereYear('ngay_ban_hanh', $nam_ban_hanh);
        }
        if ($nam_co_hieu_luc != null) {
            $query->whereYear('ngay_co_hieu_luc', $nam_co_hieu_luc);
        }
        if ($hieu_luc != null) {
            $query->where('hieu_luc', $hieu_luc);
        }
        if ($co_quan_id != null) {
            $query->where('co_quan_ban_hanh_id', $co_quan_id);
        }
        if ($loai_id != null) {
            $query->where('loai_id', $loai_id);
        }
        if ($linh_vuc_id != null) {
            $query->where('linh_vuc_id', $linh_vuc_id);
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
        $loaiVanBans =  Lookup::where('group', 'LoaiVanBanPhapLuat')->select('id', 'name')->get();
        $coQuans =  Lookup::where('group', 'CoQuanBanHanhVanBanPhapLuat')->select('id', 'name')->get();
        $linhVucs =  Lookup::where('group', 'LinhVucVanBanPhapLuat')->select('id', 'name')->get();
        $hieuLuc = [['name' => 'Còn hiệu lực', 'id' => true], ['name' => 'Hết hiệu lực', 'id' => false]];
        foreach ($loaiVanBans as $item) {
            $item['so_luong'] = VanBanPhapLuat::where('loai_id', $item['id'])->count();
        }
        foreach ($coQuans as $item) {
            $item['so_luong'] = VanBanPhapLuat::where('co_quan_ban_hanh_id', $item['id'])->count();
        }
        foreach ($linhVucs as $item) {
            $item['so_luong'] = VanBanPhapLuat::where('linh_vuc_id', $item['id'])->count();
        }
        foreach ($hieuLuc as &$item) {
            $item['so_luong'] = VanBanPhapLuat::where('hieu_luc', $item['id'])->count();
        }
        return response(['loai' => $loaiVanBans, 'co_quan' => $coQuans, 'linh_vuc' => $linhVucs, 'hieu_luc' => $hieuLuc], 200);
    }

    public function xoaVanBanPhapLuat(Request $request, $id)
    {
        try {
            $info=VanBanPhapLuat::where('id', $id)->first();
            VanBanPhapLuat::find($id)->delete();
            $user = Auth::user();
            createLog($user->id, 'delete_van_ban_phap_luat', $request->ip(), $request->header('User-Agent'), 'Xóa văn bản pháp luật '.$info['ten']);
            return response(['message' => 'Done'], 200);
        } catch (\Exception $e) {
            return response(['message' => 'Error'], 500);
        }
    }
    public function viewSearch()
    {
        $phanLoais = Lookup::where('group', 'LoaiVanBanPhapLuat')->get()->toArray();
        $banHanhs = Lookup::where('group', 'CoQuanBanHanhVanBanPhapLuat')->get()->toArray();
        $linhVucs = Lookup::where('group', 'LinhVucVanBanPhapLuat')->get()->toArray();
        $danhmucs = [
            'phan_loai' => $phanLoais,
            'ban_hanh' => $banHanhs,
            'linh_vuc' => $linhVucs
        ];
        return view('search.nolucbaoton.vanbanphapluat.legislation', [
            'lang' => Session::get('locale'),
            'danhmucs' => json_encode($danhmucs)
        ]);
    }

    public function getVanBanPhapLuatSearch(Request $request)
    {
        $page = $request->get('page', 1);
        $per_pager = $request->get('perPage', 5);
        $search = $request->get('search', null);
        $nam_ban_hanh_tu = $request->get('nam_ban_hanh_tu', null);
        $nam_ban_hanh_den = $request->get('nam_ban_hanh_den', null);
        $co_quan_id = $request->get('co_quan', null);
        $loai_id = $request->get('phan_loai', null);
        $linh_vuc = $request->get('linh_vuc', null);

        $query = VanBanPhapLuat::with(['phanLoai', 'linhVuc', 'coQuanBanHanh']);
        if ($search != null) {
            $search = trim($search);
            $query = $query->where(function ($query) use ($search) {
                $query->where('ten', 'ilike', "%{$search}%")
                    ->orWhere('noi_dung_chinh', 'ilike', "%{$search}%")
                    ->orWhere('noi_dung_toan_van', 'ilike', "%{$search}%")
                    ->orWhere('ghi_chu', 'ilike', "%{$search}%");
            });
        }
        if ($nam_ban_hanh_tu != null) {
            $query->whereYear('ngay_ban_hanh','>=', $nam_ban_hanh_tu);
        }
        if ($nam_ban_hanh_den != null) {
            $query->whereYear('ngay_ban_hanh','<=', $nam_ban_hanh_den);
        }
        if ($co_quan_id != null) {
            $query->whereIn('co_quan_ban_hanh_id', $co_quan_id);
        }
        if ($loai_id != null) {
            $query->whereIn('loai_id', $loai_id);
        }
        if ($linh_vuc != null) {
            $query->whereIn('linh_vuc_id', $linh_vuc);
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
    public function viewDetailSearch($id)
    {
        $detail = VanBanPhapLuat::where('id', $id)->with(['phanLoai', 'linhVuc', 'coQuanBanHanh'])->first();
        $files = [];
        $fileIds = json_decode($detail['files']);
        if ($detail['files'] && count($fileIds) > 0) {
            $files = DB::table('media')->whereIn('id', $fileIds)->select('id', 'name', 'disk', 'size')->get();
            $detail['fileList'] = $files->toArray();
        }else $detail['fileList'] = [];
        return view('search.nolucbaoton.vanbanphapluat.detail', [
            'data' => json_encode($detail)
        ]);
    }
    
}
