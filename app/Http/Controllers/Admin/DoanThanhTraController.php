<?php

namespace App\Http\Controllers\Admin;

use App\DoanThanhTra;
use App\Http\Controllers\Controller;
use App\Lookup;
use App\Organization;
use App\Province;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class DoanThanhTraController extends Controller
{

    public function viewIndex()
    {
        $tinhThanh = Province::select('id', 'name_vietnamese')->get();
        $coQuan = Organization::select('id', 'name_vietnamese')->get();
        $loaiHinhDoanThanhTra = Lookup::where('group', 'loai_hinh_doan_thanh_tra')->get();
        $cheDoThanhTra = Lookup::where('group', 'che_do_thanh_tra')->get();
        $danhmuc = [
            'tinh_thanh' => $tinhThanh,
            'co_quan' => $coQuan,
            'loai_hinh' => $loaiHinhDoanThanhTra,
            'che_do' => $cheDoThanhTra,
        ];
        return view('admin.resources.thanhtrakiemtra.doanthanhtra.index', ['danhmucs' => json_encode($danhmuc)]);
    }

    public function addDoanThanhTra(Request $request)
    {
        $info = $request->except('dia_ban');
        $validator = Validator::make($info, [
            'loai_hinh_id' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'message' => __('Lỗi dữ liệu'),
            ], 401, []);
        }
        try {
            $doanthanhtra = DoanThanhTra::create($info);
            $doanthanhtra->tinhThanh()->attach($request->dia_ban);
            return response(['message' => 'Done'], 200);
        } catch (\Exception $e) {
            dd($e);
            return response(['message' => 'Không thể thêm mới'], 501);
        }
    }

    public function getDoanThanhTra(Request $request)
    {
        $page = $request->get('page', 1);
        $per_pager = $request->get('perPage', 5);
        $search = $request->get('search', null);
        $loai_hinh_id = $request->get('loai_hinh_id', null);
        $che_do_id = $request->get('che_do_id', null);
        $co_quan_ky_id = $request->get('co_quan_ky_id', null);
        $co_quan_thuc_hien_id = $request->get('co_quan_thuc_hien_id', null);
        $query = DoanThanhTra::with(['cheDo', 'tinhThanh', 'loaiHinh', 'coQuanKy', 'coQuanThucHien']);
        if ($search != null) {
            $search = trim($search);
            $query = $query->where(function ($query) use ($search) {
                $query->where('so_quyet_dinh', 'ilike', "%{$search}%");
            });
        }
        if ($loai_hinh_id) {
            $query->where('loai_hinh_id', $loai_hinh_id);
        }
        if ($che_do_id) {
            $query->where('che_do_id', $che_do_id);
        }
        if ($co_quan_ky_id) {
            $query->where('co_quan_ky_id', $co_quan_ky_id);
        }
        if ($co_quan_thuc_hien_id) {
            $query->where('co_quan_thuc_hien_id', $co_quan_thuc_hien_id);
        }

        $query->orderBy('updated_at', 'DESC');
        $query->orderBy('id', 'DESC');
        $data = $query->paginate($per_pager, ['*'], 'page', $page);
        return $data;
    }

    public function updateDoanThanhTra(Request $request)
    {
        $info = $request->except('dia_ban');
        $validator = Validator::make($info, [
            'loai_hinh_id' => 'required',
            'id' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'message' => __('Lỗi dữ liệu'),
            ], 401, []);
        }
        try {
            $doanthanhtra = DoanThanhTra::find($info['id']);
            $doanthanhtra->update($info);
            $doanthanhtra->tinhThanh()->sync($request->dia_ban);
            return response(['message' => 'Done'], 200);
        } catch (\Exception $e) {
            return response(['message' => 'Không thể thêm mới'], 501);
        }
    }

    public function xoaDoanThanhTra($id)
    {
        try {
            DoanThanhTra::find($id)->delete();
            return response(['message' => 'Done'], 200);
        } catch (\Exception $e) {
            return response(['message' => 'Error'], 500);
        }
    }
    public function getChiSo()
    {
        $loaiHinhDoanThanhTra = Lookup::where('group', 'loai_hinh_doan_thanh_tra')->get();
        $cheDoThanhTra = Lookup::where('group', 'che_do_thanh_tra')->get();
        foreach ($loaiHinhDoanThanhTra as $item) {
            $item['so_luong'] = DoanThanhTra::where('loai_hinh_id', $item['id'])->count();
        }
        foreach ($cheDoThanhTra as $item) {
            $item['so_luong'] = DoanThanhTra::where('che_do_id', $item['id'])->count();
        }
        return response([
            'loai_hinh' => $loaiHinhDoanThanhTra,
            'che_do' => $cheDoThanhTra],
            200);
    }
}
