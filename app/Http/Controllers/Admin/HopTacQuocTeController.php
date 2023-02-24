<?php

namespace App\Http\Controllers\admin;

use App\HopTacQuocTe;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Lookup;
use App\Province;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

use function GuzzleHttp\json_encode;

class HopTacQuocTeController extends Controller
{
    public function viewIndex()
    {
        return view('admin.resources.hoptacquocte.index');
    }
    public function showFormAdd()
    {
        $tinhThanh = Province::select('name', 'id', 'name_vietnamese')->get();
        $danhNghia = Lookup::where('group', 'DanhNghia')->get();
        $phanCap = Lookup::where('group', 'phanCap')->get();
        $phanLoai = Lookup::where('group', 'phanLoaiHopTacQuocTe')->get();
        $data = [
            'tinh_thanh' => $tinhThanh,
            'danh_nghia' => $danhNghia,
            'phan_cap' => $phanCap,
            'phan_loai' => $phanLoai
        ];
        return view('admin.resources.hoptacquocte.add', ['data' => json_encode($data)]);
    }
    public function showFormEdit($id)
    {
        $tinhThanh = Province::select('name', 'id', 'name_vietnamese')->get();
        $danhNghia = Lookup::where('group', 'DanhNghia')->get();
        $phanCap = Lookup::where('group', 'phanCap')->get();
        $phanLoai = Lookup::where('group', 'phanLoaiHopTacQuocTe')->get();
        $danhMucs = [
            'tinh_thanh' => $tinhThanh,
            'danh_nghia' => $danhNghia,
            'phan_cap' => $phanCap,
            'phan_loai' => $phanLoai
        ];
        $data = HopTacQuocTe::with(['phanCap', 'danhNghia', 'phanLoai'])->where('id', $id)->first();
        $data['fileList'] = [];
        $fileIds = json_decode($data['files']);
        if ($data['files'] && count($fileIds) > 0) {
            $files = DB::table('media')->whereIn('id', $fileIds)->select('id', 'name', 'disk', 'size')->get();
            $data['fileList'] = $files->toArray();
        }

        return view('admin.resources.hoptacquocte.edit', ['danhmucs' => json_encode($danhMucs), 'data' => json_encode($data)]);
    }
    public function addHopTacQuocTe(Request $request)
    {
        $info = $request->only('ten', 'co_quan_cap', 'phan_loai_id', 'tinh_chat', 'ngay_ban_hanh', 'ngay_hieu_luc', 'ngay_het_han', 'doi_tac', 'co_quan_chu_tri', 'phan_cap_id', 'danh_nghia_id', 'nguoi_ky', 'hieu_luc', 'noi_dung_chinh', 'tinh_thanh_id', 'noi_dung_toan_van', 'ghi_chu', 'files');
        $validator = Validator::make($info, [
            'ten' => 'required|unique:hop_tac_quoc_tes',
        ], [
            'ten.unique' => 'Tên văn bản/dự án đã tồn tại',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'message' => __('message.vaidator'),
                'error' => $validator->errors()
            ], 200, []);
        }
        if (isset($info['ngay_ban_hanh']) && isset($info['ngay_hieu_luc']) && $info['ngay_ban_hanh'] && $info['ngay_hieu_luc'] && strtotime($info['ngay_ban_hanh']) > strtotime($info['ngay_hieu_luc'])) {
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
            HopTacQuocTe::create($info);
            $user = Auth::user();
            createLog($user->id, 'add_htqt', $request->ip(), $request->header('User-Agent'), 'Thêm mới hợp tác quốc tế '.$info['ten']);
            return response(['message' => 'Done'], 200);
        } catch (\Exception $e) {
            return response(['message' => 'Không thể thêm mới'], 501);
        }
    }
    public function editHopTacQuocTe(Request $request)
    {
        $info = $request->only('id', 'ten', 'phan_loai_id', 'co_quan_cap', 'tinh_chat', 'ngay_ban_hanh', 'ngay_hieu_luc', 'ngay_het_han', 'doi_tac', 'co_quan_chu_tri', 'phan_cap_id', 'danh_nghia_id', 'nguoi_ky', 'hieu_luc', 'noi_dung_chinh', 'tinh_thanh_id', 'noi_dung_toan_van', 'ghi_chu', 'files');
        $validator = Validator::make($info, [
            'ten' => 'required',
            'id' => 'required'
        ]);
        if ($validator->fails()) {
            return response()->json([
                'message' => __('Lỗi dữ liệu'),
            ], 401, []);
        }
        $trungLap = HopTacQuocTe::where('ten', $info['ten'])->where('id', '!=', $info['id'])->first();
        if ($trungLap) {
            return response()->json([
                'message' => __('Tên văn bản/dự án đã tồn tại'),
            ], 200, []);
        }
        if (isset($info['ngay_ban_hanh']) && isset($info['ngay_hieu_luc']) && $info['ngay_ban_hanh'] && $info['ngay_hieu_luc'] && strtotime($info['ngay_ban_hanh']) > strtotime($info['ngay_hieu_luc'])) {
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
            HopTacQuocTe::find($info['id'])->update($info);
            $user = Auth::user();
            createLog($user->id, 'update_htqt', $request->ip(), $request->header('User-Agent'), 'Cập nhật hợp tác quốc tế '.$info['ten']);
            return response(['message' => 'Done'], 200);
        } catch (\Exception $e) {
            return response(['message' => 'Không thể thêm mới'], 501);
        }
    }

    public function getHopTacQuocTe(Request $request)
    {
        $page = $request->get('page', 1);
        $per_pager = $request->get('perPage', 5);
        $search = $request->get('search', null);
        $nam_ban_hanh = $request->get('nam_ban_hanh', null);
        $tinh_chat = $request->get('tinh_chat', null);
        $nam_het_han = $request->get('nam_het_han', null);
        $query = HopTacQuocTe::with(['phanCap', 'danhNghia']);
        if ($search != null) {
            $search = trim($search);
            $query = $query->where(function ($query) use ($search) {
                $query->where('ten', 'ilike', "%{$search}%")
                    ->orWhere('co_quan_chu_tri', 'ilike', "%{$search}%")
                    ->orWhere('doi_tac', 'ilike', "%{$search}%")
                    ->orWhere('noi_dung_chinh', 'ilike', "%{$search}%")
                    ->orWhere('noi_dung_toan_van', 'ilike', "%{$search}%")
                    ->orWhere('ghi_chu', 'ilike', "%{$search}%")
                    ->orWhereHas('phanCap', function ($query) use ($search) {
                        $query->where('name', 'ilike', "%{$search}%");
                    })
                    ->orWhereHas('danhNghia', function ($query) use ($search) {
                        $query->where('name', 'ilike', "%{$search}%");
                    });
            });
        }
        if ($nam_ban_hanh != null) {
            $query->whereYear('ngay_ban_hanh', $nam_ban_hanh);
        }
        if ($nam_het_han != null) {
            $query->whereYear('ngay_het_han', $nam_het_han);
        }
        if ($tinh_chat != null) {
            $query->where('tinh_chat', $tinh_chat);
        }

        $query->orderBy('updated_at', 'DESC');
        $query->orderBy('id', 'DESC');
        $data =  $query->paginate($per_pager, ['*'], 'page', $page);
        return $data;
    }

    public function viewSearch()
    {
        return view('search.nolucbaoton.cooperation', [
            'lang' => Session::get('locale'),
        ]);
    }

    public function xoaHopTacQuocTe(Request $request, $id)
    {
        try {
            $info=HopTacQuocTe::where('id',$id)->first();
            HopTacQuocTe::find($id)->delete();
            $user = Auth::user();
            createLog($user->id, 'delete_htqt', $request->ip(), $request->header('User-Agent'), 'Xóa hợp tác quốc tế '.$info['ten']);
            return response(['message' => 'Done'], 200);
        } catch (\Exception $e) {
            return response(['message' => 'Error'], 500);
        }
    }
}
