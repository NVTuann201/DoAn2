<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\OTieuChuan;
use App\ProtectedArea;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class OTieuChuanController extends Controller
{
    public function viewIndex()
    {
        $diaDiem = ProtectedArea::select('id', 'orig_name', 'desig', 'name')->get();
        return view('admin.resources.otieuchuan.index', ['diadiems' => json_encode($diaDiem)]);
    }

    public function add(Request $request)
    {
        $info = $request->only(
            'ten',
            'khu_sinh_thai',
            'do_cao',
            'hinh_dang',
            'kich_thuoc',
            'dia_diem_id',
            'vi_tri',
            'geom'
        );
        $validator = Validator::make($info, [
            'ten' => 'required|unique:o_tieu_chuans',
        ],[
            'ten.unique' => 'Tên ô tiêu chuẩn đã tồn tại'
        ]);
        if ($validator->fails()) {
            return response()->json([
                'message' => __('message.vaidator'),
                'error' => $validator->errors()
            ], 200, []);
        }
        try {
            $info['geom'] = $info['geom'] ? DB::raw("ST_GeomFromGeoJSON('" . json_encode($info['geom']) . "')") : null;

            OTieuChuan::create($info);
            $user = Auth::user();
            createLog($user->id, 'add_o_tieu_chuan', $request->ip(), $request->header('User-Agent'), 'Thêm mới ô tiêu chuẩn '.$info['ten']);
            return response(['message' => 'Done'], 200);
        } catch (\Exception $e) {
            dd($e);
            return response(['message' => 'Không thể thêm mới'], 501);
        }
    }
    public function edit(Request $request)
    {
        $info = $request->only(
            'id',
            'ten',
            'khu_sinh_thai',
            'kich_thuoc',
            'do_cao',
            'hinh_dang',
            'dia_diem_id',
            'vi_tri',
            'geom'
        );
        $validator = Validator::make($info, [
            'ten' => 'required',
            'id' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'message' => __('Lỗi dữ liệu'),
            ], 401, []);
        }
        $trungLap=OTieuChuan::where('ten', $info['ten'])->where('id','!=', $info['id'] )->first();
        if($trungLap){
            return response()->json([
                'message' => __('Tên mô hình đã tồn tại'),
            ], 200, []);
        }
        try {
            $info['geom'] = $info['geom'] ? DB::raw("ST_GeomFromGeoJSON('" . json_encode($info['geom']) . "')") : null;
            OTieuChuan::find($info['id'])->update($info);
            $user = Auth::user();
            createLog($user->id, 'update_o_tieu_chuan', $request->ip(), $request->header('User-Agent'), 'Cập nhật ô tiêu chuẩn '.$info['ten']);
            return response(['message' => 'Done'], 200);
        } catch (\Exception $e) {
            dd($e);
            return response(['message' => 'Không thể thêm mới'], 501);
        }
    }

    public function delete(Request $request, $id)
    {
        try {
            $info=OTieuChuan::where('id', $id)->first();
            OTieuChuan::find($id)->delete();
            $user = Auth::user();
            createLog($user->id, 'delete_o_tieu_chuan', $request->ip(), $request->header('User-Agent'), 'Xóa ô tiêu chuẩn '.$info['ten']);
            return response(['message' => 'Done'], 200);
        } catch (\Exception $e) {
            return response(['message' => 'Error'], 501);
        }
    }
    public function getOTieuChuan(Request $request)
    {
        $page = $request->get('page', 1);
        $per_pager = $request->get('perPage', 5);
        $search = $request->get('search', null);
        $query = OTieuChuan::with(['diaDiem']);
        if ($search != null) {
            $search = trim($search);
            $query = $query->where(function ($query) use ($search) {
                $query->where('ten', 'ilike', "%{$search}%")
                    ->orWhere('khu_sinh_thai', 'ilike', "%{$search}%")
                    ->orWhere('vi_tri', 'ilike', "%{$search}%")
                    ->orWhere('hinh_dang', 'ilike', "%{$search}%");
                    // ->orWhere('do_cao', 'ilike', "%{$search}%");
            });
        }
        $query->orderBy('updated_at', 'DESC');
        $query->orderBy('id', 'DESC');
        $data = $query->paginate($per_pager, ['*'], 'page', $page);
        return $data;
    }
}
