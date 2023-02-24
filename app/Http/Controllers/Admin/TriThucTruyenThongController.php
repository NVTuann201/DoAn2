<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Lookup;
use App\NguonGen;
use App\NguonGenTriThucTruyenThong;
use App\TriThucTruyenThong;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class TriThucTruyenThongController extends Controller
{
    public function ViewIndex()
    {
        $nhom = Lookup::where('group', 'nhomtrithuctruyenthong')->get();
        return view('admin.category.trithuctruyenthong.index', ['nhomtrithucs' => json_encode($nhom)]);
    }
    public function getNguonGenSelect(Request $request)
    {
        $search = $request->get('search', null);
        $query = NguonGen::query();
        if ($search) {
            $search = trim($search);
            $query = $query->where('ten', 'ilike', "%{$search}%")
                ->orWhere('ten_dan_toc', 'ilike', "%{$search}%")
                ->orWhere('ten_thong_thuong', 'ilike', "%{$search}%")
                ->orWhere('ghi_chu', 'ilike', "%{$search}%")
                ->orWhere('dac_diem', 'ilike', "%{$search}%")
                ->orWhere('xuat_xu', 'ilike', "%{$search}%")
                ->orWhere('ten_khoa_hoc', 'ilike', "%{$search}%");
        }
        return $query->take(50)->get();
    }

    public function addTriThucTruyenThong(Request $request)
    {
        $data = $request->only('nhom_id', 'ten', 'nguoi_luu_giu', 'noi_luu_giu', 'mo_ta', 'cap_giay_chung_nhan', 'ghi_chu');
        $nguonGen = $request->get('nguon_gen', []);
        $validator = Validator::make($data, [
            'ten' => 'required|unique:tri_thuc_truyen_thongs',
            'nhom_id' => 'required',
        ], [
            'ten.unique' => 'Tri thức truyền thông đã tồn tại',
            'nhom_id.required' => 'Trường Nhóm là bắt buộc'
        ]);
        if ($validator->fails()) {
            return response()->json([
                'message' => __('message.vaidator'),
                'error' => $validator->errors()
            ], 200, []);
        }
        try {
            DB::beginTransaction();
            $triThuc = TriThucTruyenThong::create($data);
            $user = Auth::user();
            createLog($user->id, 'add_tri_thuc_truyen_thong', $request->ip(), $request->header('User-Agent'), 'Thêm mới tri thức truyền thông '.$data['ten']);
            if ($nguonGen && count($nguonGen) > 0) {
                foreach ($nguonGen as $item) {
                    NguonGenTriThucTruyenThong::create([
                        'nguon_gen_id' => $item['id'],
                        'tri_thuc_truyen_thong_id' => $triThuc->id
                    ]);
                }
            }
            DB::commit();
            return response(['message' => 'Done'], 200);
        } catch (\Exception $e) {
            DB::rollback();
            return response(['message' => 'Error'], 500);
        }
    }

    public function updateTriThucTruyenThong(Request $request)
    {
        $data = $request->only('id', 'nhom_id', 'ten', 'nguoi_luu_giu', 'noi_luu_giu', 'mo_ta', 'cap_giay_chung_nhan', 'ghi_chu');
        $nguonGen = $request->get('nguon_gen', []);
        $validator = Validator::make($data, [
            'id' => 'required',
            'ten' => 'required',
            'nhom_id' => 'required',
        ]);
        $trunglap = TriThucTruyenThong::where('ten', $data['ten'])->where('id', '!=', $data['id'])->first();
        if ($trunglap) {
            return response()->json([
                'message' => __('Trùng lặp dữ liệu'),
            ], 200, []);
        }
        if ($validator->fails()) {
            return response()->json([
                'message' => __('message.vaidator'),
                'error' => $validator->errors()
            ], 200, []);
        }
        try {
            DB::beginTransaction();
            TriThucTruyenThong::find($data['id'])->update($data);
            $user = Auth::user();
            createLog($user->id, 'update_tri_thuc_truyen_thong', $request->ip(), $request->header('User-Agent'), 'Cập nhật tri thức truyền thông '.$data['ten']);
            NguonGenTriThucTruyenThong::where('tri_thuc_truyen_thong_id', $data['id'])->delete();
            if ($nguonGen && count($nguonGen) > 0) {
                foreach ($nguonGen as $item) {
                    NguonGenTriThucTruyenThong::create([
                        'nguon_gen_id' => $item['id'],
                        'tri_thuc_truyen_thong_id' => $data['id']
                    ]);
                }
            }
            DB::commit();
            return response(['message' => 'Done'], 200);
        } catch (\Exception $e) {
            DB::rollback();
            return response(['message' => 'Error'], 500);
        }
    }

    public function getTriThucTruyenThong(Request $request)
    {
        $page = $request->get('page', 1);
        $per_pager = $request->get('perPage', 5);
        $search = $request->get('search', null);
        $nhom_id = $request->get('nhom_id', null);
        $query = TriThucTruyenThong::with(['nhom', 'nguonGen']);
        if ($search != null) {
            $search = trim($search);
            $query = $query->where(function ($query) use ($search) {
                $query->where('ten', 'ilike', "%{$search}%")
                    ->orWhere('ghi_chu', 'ilike', "%{$search}%")
                    ->orWhere('cap_giay_chung_nhan', 'ilike', "%{$search}%")
                    ->orWhere('mo_ta', 'ilike', "%{$search}%");
            });
        }
        if ($nhom_id != null) {
            $query->where('nhom_id', $nhom_id);
        }
        $query->orderBy('updated_at', 'DESC');
        $query->orderBy('id', 'DESC');
        $data =  $query->paginate($per_pager, ['*'], 'page', $page);
        return $data;
    }

    public function xoaTriThucTruyenThong(Request $request, $id)
    {
        try {
            $info=TriThucTruyenThong::where('id', $id)->first();
            TriThucTruyenThong::find($id)->delete();
            $user = Auth::user();
            createLog($user->id, 'delete_tri_thuc_truyen_thong', $request->ip(), $request->header('User-Agent'), 'Xóa tri thức truyền thông '.$info['ten']);
            return response(['message' => 'Success'], 200);
        } catch (\Exception $e) {
            return response(['message' => 'Error'], 500);
        }
    }

    public function getChiSo()
    {
        $nhom = Lookup::where('group', 'nhomtrithuctruyenthong')->get();
        foreach ($nhom as $item) {
            $item['so_luong'] = TriThucTruyenThong::where('nhom_id', $item['id'])->count();
        }
        return response(['nhom' => $nhom], 200);
    }
}
