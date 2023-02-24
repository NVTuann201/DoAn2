<?php

namespace App\Http\Controllers\Admin;

use App\District;
use App\Http\Controllers\Controller;
use App\NoiLuuGiu;
use App\Province;
use DB;
use function GuzzleHttp\json_encode;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class NoiLuuGiuNguonGenController extends Controller
{
    public function getTinhThanhQuanHuyen()
    {
        $tinhThanh = Province::select('name', 'id', 'name_vietnamese')->get();
        $quanHuyen = District::select('name', 'id', 'name_vietnamese', 'province_id')->get();
        return ['tinh_thanh' => $tinhThanh, 'quan_huyen' => $quanHuyen];
    }
    public function addNoiLuuGiu(Request $request)
    {
        $data = $request->only(
            'ten',
            'quan_huyen',
            'thon_xa',
            'nguoi_so_huu',
            'thong_tin_lien_he',
            'ghi_chu',
            'loai_doi_tuong',
            'doi_tuong_id',
            'geom'
        );
        $validator = Validator::make($data, [
            'ten' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'message' => __('Lỗi dữ liệu'),
            ], 401, []);
        }

        if ($data['quan_huyen']) {
            $data['quan_huyen'] = json_encode($data['quan_huyen']);
        } else {
            $data['quan_huyen'] = null;
        }
        try {
            if ($data['geom']) {
                $data['geom'] = DB::raw("ST_GeomFromGeoJSON('" . json_encode($data['geom']) . "')");
            }
            NoiLuuGiu::create($data);
            $user = Auth::user();
            createLog($user->id, 'add_noi_luu_giu_nguon_gen', $request->ip(), $request->header('User-Agent'), 'Thêm mới noiư luu giữ nguồn gen '.$data['name']);
            return response(['message' => 'Done'], 200);
        } catch (\Exception $e) {
            dd($e);
            return response(['message' => 'Không thể thêm mới'], 501);
        }
    }
    public function editNoiLuuGiu(Request $request)
    {
        $data = $request->only(
            'id',
            'ten',
            'quan_huyen',
            'thon_xa',
            'nguoi_so_huu',
            'thong_tin_lien_he',
            'ghi_chu',
            'loai_doi_tuong',
            'doi_tuong_id',
            'geom'
        );
        $validator = Validator::make($data, [
            'ten' => 'required',
            'id' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'message' => __('Lỗi dữ liệu'),
            ], 401, []);
        }
        if ($data['quan_huyen']) {
            $data['quan_huyen'] = json_encode($data['quan_huyen']);
        } else {
            $data['quan_huyen'] = null;
        }
        try {
            if ($data['geom']) {
                $data['geom'] = DB::raw("ST_GeomFromGeoJSON('" . json_encode($data['geom']) . "')");
            } else {
                $data['geom'] = null;
            }
            NoiLuuGiu::find($data['id'])->update($data);
            $user = Auth::user();
            createLog($user->id, 'update_noi_luu_giu_nguon_gen', $request->ip(), $request->header('User-Agent'), 'Cập nhật noiư luu giữ nguồn gen '.$data['name']);
            return response(['message' => 'Done'], 200);
        } catch (\Exception $e) {
            return response(['message' => 'Không thể thêm mới'], 501);
        }
    }
    public function getNoiLuuGiu(Request $request)
    {
        $page = $request->get('page', 1);
        $per_pager = $request->get('perPage', 5);
        $search = $request->get('search', null);
        $query = NoiLuuGiu::query();
        if ($search != null) {
            $search = trim($search);
            $query = $query->where(function ($query) use ($search) {
                $query->where('ten', 'ilike', "%{$search}%")
                    ->orWhere('thon_xa', 'ilike', "%{$search}%")
                    ->orWhere('nguoi_so_huu', 'ilike', "%{$search}%")
                    ->orWhere('thong_tin_lien_he', 'ilike', "%{$search}%")
                    ->orWhere('ghi_chu', 'ilike', "%{$search}%");
            });
        }

        $query->orderBy('updated_at', 'DESC');
        $query->orderBy('id', 'DESC');
        $data = $query->paginate($per_pager, ['*'], 'page', $page);
        foreach ($data as &$item) {
            $quanHuyen = [];
            $tinhThanh = [];
            if ($item['tinh_thanh']) {
                $tinhThanh = Province::whereIn('id', json_decode($item['tinh_thanh']))->select('name', 'id', 'name_vietnamese')->get()->toArray();
            }
            if ($item['quan_huyen']) {
                $quanHuyen = District::whereIn('id', json_decode($item['quan_huyen']))->select('name', 'id', 'name_vietnamese', 'province_id')->with('Province')->get()->toArray();
            }
            $item['quan_huyens'] = $quanHuyen;
            $item['tinh_thanhs'] = $tinhThanh;
        }
        return $data;
    }
    public function xoaNoiLuuTru(Request $request, $id)
    {
        try {
            $info=NoiLuuGiu::where('id',$id)->first();
            NoiLuuGiu::find($id)->delete();
            $user = Auth::user();
            createLog($user->id, 'update_noi_luu_giu_nguon_gen', $request->ip(), $request->header('User-Agent'), 'Cập nhật noiư luu giữ nguồn gen '.$info['name']);
            return response(['message' => 'Done'], 200);
        } catch (\Exception $e) {
            return response(['message' => 'Error'], 500);
        }
    }
}
