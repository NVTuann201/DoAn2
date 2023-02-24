<?php

namespace App\Http\Controllers\Admin;

use App\CoSoBaoTon;
use App\District;
use App\Http\Controllers\Controller;
use App\Lookup;
use App\ProtectedArea;
use App\Province;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class CoSoBaoTonController extends Controller
{
    public function viewIndex()
    {
        return view('admin.resources.cosobaoton.index');
    }
    public function showFormAdd()
    {
        $loaiHinh = Lookup::where('group', 'loaiHinhCoSoBaoTon')->get();
        $loaiHinhToChuc = Lookup::where('group', 'loaiHinhToChucCoSoBaoTon')->get();
        $QuanLy = Lookup::where('group', 'phanCap')->get();
        $diaDiem = ProtectedArea::select('id', 'orig_name', 'desig', 'name')->get();
        $quanHuyen = District::select('name', 'id', 'name_vietnamese')->get();
        $danhmucs = [
            'loai_hinh' => $loaiHinh,
            'loai_hinh_to_chuc' => $loaiHinhToChuc,
            'quan_ly' => $QuanLy,
            'dia_diem' => $diaDiem,
            'quan_huyen' => $quanHuyen,
        ];
        return view('admin.resources.cosobaoton.add', ['danhmucs' => json_encode($danhmucs)]);
    }
    public function add(Request $request)
    {
        $info = $request->only(
            'ten',
            'dia_chi',
            'co_quan_chu_quan',
            'mo_ta',
            'giay_phep',
            'dien_tich',
            'loai_hinh_id',
            'loai_hinh_to_chuc_id',
            'chuc_nang',
            'don_vi_cap',
            'ngay_cap',
            'quan_ly_id',
            'co_so_vat_chat',
            'quy_trinh_ky_thuat',
            'nhan_luc',
            'tai_chinh',
            'hinh_thuc_luu_giu',
            'dien_tich_luu_giu',
            'dia_diem_id',
            'quan_huyen_id',
            'ghi_chu',
            'geom',
            'co_quan_quan_ly',
            'hinh_thuc_bao_ton',
            'files',
            'status'
        );
        $validator = Validator::make($info, [
            'ten' => 'required|unique:co_so_bao_tons',
        ],[
            'ten.unique' => 'Tên cơ sở đã tồn tại'
        ]);
        if ($validator->fails()) {
            return response()->json([
                'message' => __('message.vaidator'),
                'error' => $validator->errors()
            ], 200, []);
        }
        try {
            $info['geom'] = $info['geom'] ? DB::raw("ST_GeomFromGeoJSON('" . json_encode($info['geom']) . "')") : null;
            if ($info['files']) {
                $info['files'] = json_encode($info['files']);
            }else $info['files'] = null;
            CoSoBaoTon::create($info);
            $user = Auth::user();
            createLog($user->id,'add_co_so_bao_ton',$request->ip(),$request->header('User-Agent'),'Thêm mới cơ sở bảo tồn '.$info['ten']);
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
            'dia_chi',
            'co_quan_chu_quan',
            'mo_ta',
            'giay_phep',
            'dien_tich',
            'loai_hinh_id',
            'loai_hinh_to_chuc_id',
            'chuc_nang',
            'don_vi_cap',
            'ngay_cap',
            'quan_ly_id',
            'co_so_vat_chat',
            'quy_trinh_ky_thuat',
            'nhan_luc',
            'tai_chinh',
            'hinh_thuc_luu_giu',
            'dien_tich_luu_giu',
            'dia_diem_id',
            'quan_huyen_id',
            'ghi_chu',
            'geom',
            'co_quan_quan_ly',
            'hinh_thuc_bao_ton',
            'files',
            'status'
        );
        $validator = Validator::make($info, [
            'id' => 'required',
            'ten' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'message' => __('Lỗi dữ liệu'),
            ], 401, []);
        }
        $trungLap=CoSoBaoTon::where('ten', $info['ten'])->where('id','!=', $info['id'] )->first();
        if($trungLap){
            return response()->json([
                'message' => __('Tên cơ sở bảo tồn đã tồn tại'),
            ], 200, []);
        }
        try {
            $info['geom'] = $info['geom'] ? DB::raw("ST_GeomFromGeoJSON('" . json_encode($info['geom']) . "')") : null;
            if ($info['files']) {
                $info['files'] = json_encode($info['files']);
            }else $info['files'] = null;
            CoSoBaoTon::find($info['id'])->update($info);
            $user = Auth::user();
            createLog($user->id,'update_co_so_bao_ton',$request->ip(),$request->header('User-Agent'),'Cập nhật cơ sở bảo tồn '.$info['ten']);
            return response(['message' => 'Done'], 200);
        } catch (\Exception $e) {
            dd($e);
            return response(['message' => 'Không thể thêm mới'], 501);
        }
    }
    public function getCoSoBaoTon(Request $request)
    {
        $page = $request->get('page', 1);
        $per_pager = $request->get('perPage', 5);
        $search = $request->get('search', null);
        $loai_hinh_id = $request->get('loai_hinh_id', null);
        $loai_hinh_to_chuc_id = $request->get('loai_hinh_to_chuc_id', null);
        $quan_ly_id = $request->get('quan_ly_id', null);
        $query = CoSoBaoTon::with(['loaiHinhToChuc', 'diaDiem', 'loaiHinh']);
        if ($search != null) {
            $search = trim($search);
            $query = $query->where(function ($query) use ($search) {
                $query->where('ten', 'ilike', "%{$search}%")
                    ->orWhere('ghi_chu', 'ilike', "%{$search}%")
                    ->orWhere('chuc_nang', 'ilike', "%{$search}%")
                    ->orWhere('co_quan_chu_quan', 'ilike', "%{$search}%")
                    ->orWhere('mo_ta', 'ilike', "%{$search}%");
            });
        }
        if ($loai_hinh_id) {
            $query = $query->where('loai_hinh_id', $loai_hinh_id);
        }
        if ($loai_hinh_to_chuc_id) {
            $query = $query->where('loai_hinh_to_chuc_id', $loai_hinh_to_chuc_id);
        }
        if ($quan_ly_id) {
            $query = $query->where('quan_ly_id', $quan_ly_id);
        }

        $query->orderBy('updated_at', 'DESC');
        $query->orderBy('id', 'DESC');
        $data = $query->paginate($per_pager, ['*'], 'page', $page);
        return $data;
    }
    public function showFormEdit($id)
    {
        $loaiHinh = Lookup::where('group', 'loaiHinhCoSoBaoTon')->get();
        $loaiHinhToChuc = Lookup::where('group', 'loaiHinhToChucCoSoBaoTon')->get();
        $QuanLy = Lookup::where('group', 'phanCap')->get();
        $diaDiem = ProtectedArea::select('id', 'orig_name', 'desig', 'name')->get();
        $quanHuyen = District::select('name', 'id', 'name_vietnamese')->get();       
        $danhmucs = [
            'loai_hinh' => $loaiHinh,
            'loai_hinh_to_chuc' => $loaiHinhToChuc,
            'quan_ly' => $QuanLy,
            'dia_diem' => $diaDiem,
            'quan_huyen' => $quanHuyen,
        ];
        $data = CoSoBaoTon::where('id', $id)->with(['loaiHinhToChuc', 'diaDiem', 'loaiHinh', 'quanLy', 'quanHuyen'])->first();
        $files = [];
        $fileIds = json_decode($data['files']);
        if ($data['files'] && count($fileIds) > 0) {
            $files = DB::table('media')->whereIn('id', $fileIds)->select('id', 'name', 'disk', 'size')->get();
            $data['fileList'] = $files->toArray();
        } else $data['fileList'] = [];
        
        return view('admin.resources.cosobaoton.edit', ['danhmucs' => json_encode($danhmucs), 'data' => json_encode($data)]);
    }

    public function delete(Request $request, $id)
    {
        try {
            $info=CoSoBaoTon::where('id', $id)->first();
            CoSoBaoTon::find($id)->delete();
            $user = Auth::user();
            createLog($user->id,'delete_co_so_bao_ton',$request->ip(),$request->header('User-Agent'),'Xóa cơ sở bảo tồn '.$info['ten']);
            return response(['message' => 'Done'], 200);
        } catch (\Exception $e) {
            return response(['message' => 'Error'], 501);
        }
    }

    public function getChiSo()
    {
        $loaiHinh = Lookup::where('group', 'loaiHinhCoSoBaoTon')->select('id', 'name')->get();
        $loaiHinhToChuc = Lookup::where('group', 'loaiHinhToChucCoSoBaoTon')->select('id', 'name')->get();
        $QuanLy = Lookup::where('group', 'phanCap')->select('id', 'name')->get();

        foreach ($loaiHinh as $item) {
            $item['so_luong'] = CoSoBaoTon::where('loai_hinh_id', $item['id'])->count();
        }
        foreach ($loaiHinhToChuc as $item) {
            $item['so_luong'] = CoSoBaoTon::where('loai_hinh_to_chuc_id', $item['id'])->count();
        }
        foreach ($QuanLy as $item) {
            $item['so_luong'] = CoSoBaoTon::where('quan_ly_id', $item['id'])->count();
        }

        return response(['loai_hinh' => $loaiHinh, 'loai_hinh_to_chuc' => $loaiHinhToChuc, 'quan_ly' => $QuanLy], 200);
    }
}
