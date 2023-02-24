<?php

namespace App\Http\Controllers\Admin;

use App\DichVuHeSinhThai;
use App\HeSinhThai;
use App\HeSinhThaiDichVu;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Lookup;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class DichVuHeSinhThaiController extends Controller
{
    public function viewIndex()
    {
        $phanLoai = Lookup::where('group', 'phanloaidichvuhesinhthai')->get();
        $heSinhThai = HeSinhThai::with(['heSinhThai'])->select('id', 'he_sinh_thai_lookup_id', 'ten')->get();
        $danhmucs = [
            'phan_loai' => $phanLoai,
            'he_sinh_thai' => $heSinhThai
        ];
        return view('admin.resources.hesinhthai.dichvu', ['danhmucs' => json_encode($danhmucs)]);
    }


    public function add(Request $request)
    {
        $info = $request->only(
            'ten',
            'phan_loai_id',
            'gia_tri_luong_gia',
            'ghi_chu',
            'mo_ta',
            'nguon_du_lieu'
        );
        $heSinhThai = $request->get('he_sinh_thai_id', []);
        $validator = Validator::make($info, [
            'ten' => 'required|unique:dich_vu_he_sinh_thais',
            'phan_loai_id' => 'required'
        ], [
            'ten.unique' => 'Tên dịch vụ đã tồn tại'
        ]);
        if ($validator->fails()) {
            return response()->json([
                'message' => __('message.vaidator'),
                'error' => $validator->errors()
            ], 200, []);
        }
        try {
            DB::beginTransaction();
            $dichVu = DichVuHeSinhThai::create($info);
            if ($heSinhThai && count($heSinhThai)) {
                foreach ($heSinhThai as $item) {
                    HeSinhThaiDichVu::create([
                        'he_sinh_thai_id' => $item,
                        'dich_vu_he_sinh_thai_id' => $dichVu->id
                    ]);
                    $user = Auth::user();
                    createLog($user->id, 'add_dich_vu_hst', $request->ip(), $request->header('User-Agent'), 'Thêm mới dịch vụ hệ sinh thái ' . $info['ten']);
                }
            }
            DB::commit();
            return response(['message' => 'Done'], 200);
        } catch (\Exception $e) {
            dd($e);
            return response(['message' => 'Không thể thêm mới'], 501);
        }
    }
    public function update(Request $request)
    {
        $info = $request->only(
            'id',
            'ten',
            'phan_loai_id',
            'gia_tri_luong_gia',
            'ghi_chu',
            'mo_ta',
            'nguon_du_lieu'
        );
        $heSinhThai = $request->get('he_sinh_thai_id', []);
        $validator = Validator::make($info, [
            'ten' => 'required',
            'phan_loai_id' => 'required',
            'id' => 'required'
        ]);
        $trungLap = DichVuHeSinhThai::where('ten', $info['ten'])->where('id', '!=', $info['id'])->first();
        if ($trungLap) {
            return response()->json([
                'message' => __('Tên cơ sở bảo tồn đã tồn tại'),
            ], 200, []);
        }
        if ($validator->fails()) {
            return response()->json([
                'message' => __('Lỗi dữ liệu'),
            ], 401, []);
        }
        try {
            DB::beginTransaction();
            DichVuHeSinhThai::find($info['id'])->update($info);
            HeSinhThaiDichVu::where('dich_vu_he_sinh_thai_id', $info['id'])->delete();
            if ($heSinhThai && count($heSinhThai)) {
                foreach ($heSinhThai as $item) {
                    HeSinhThaiDichVu::create([
                        'he_sinh_thai_id' => $item,
                        'dich_vu_he_sinh_thai_id' => $info['id']
                    ]);
                    $user = Auth::user();
                    createLog($user->id, 'update_dich_vu_hst', $request->ip(), $request->header('User-Agent'), 'Cập nhật dịch vụ hệ sinh thái ' . $info['ten']);
                }
            }
            DB::commit();
            return response(['message' => 'Done'], 200);
        } catch (\Exception $e) {
            dd($e);
            return response(['message' => 'Không thể thêm mới'], 501);
        }
    }
    public function getDichVu(Request $request)
    {
        $page = $request->get('page', 1);
        $per_pager = $request->get('perPage', 5);
        $search = $request->get('search', null);
        $phanLoaiId = $request->get('phan_loai_id', null);
        $query = DichVuHeSinhThai::with(['heSinhThai', 'phanLoai']);
        if ($search != null) {
            $search = trim($search);
            $query = $query->where(function ($query) use ($search) {
                $query->where('ten', 'ilike', "%{$search}%")
                    ->orWhere('ghi_chu', 'ilike', "%{$search}%")
                    ->orWhere('mo_ta', 'ilike', "%{$search}%");
            });
        }
        if ($phanLoaiId) {
            $query->where('phan_loai_id', $phanLoaiId);
        }

        $query->orderBy('updated_at', 'DESC');
        $query->orderBy('id', 'DESC');
        $data =  $query->paginate($per_pager, ['*'], 'page', $page);
        return $data;
    }

    public function delete(Request $request, $id)
    {
        try {
            $info=DichVuHeSinhThai::where('id', $id)->first();
            DichVuHeSinhThai::find($id)->delete();
            $user = Auth::user();
            createLog($user->id, 'delete_dich_vu_hst', $request->ip(), $request->header('User-Agent'), 'Xóa dịch vụ hệ sinh thái '.$info['ten']);
            return response(['message' => 'Done'], 200);
        } catch (\Exception $e) {
            return response(['message' => 'Error'], 501);
        }
    }

    public function getChiSo()
    {
        $phanLoai = Lookup::where('group', 'phanloaidichvuhesinhthai')->select('id', 'name')->get();

        foreach ($phanLoai as $item) {
            $item['so_luong'] = DichVuHeSinhThai::where('phan_loai_id', $item['id'])->count();
        }
        return response(['phan_loai' => $phanLoai], 200);
    }
}
