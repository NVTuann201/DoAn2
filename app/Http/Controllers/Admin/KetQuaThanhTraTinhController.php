<?php

namespace App\Http\Controllers\Admin;

use App\DoanThanhTra;
use App\Http\Controllers\Controller;
use App\KetQuaThanhTraTinh;
use App\Organization;
use App\Province;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class KetQuaThanhTraTinhController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $coQuan = Organization::select('id', 'name_vietnamese as name')->get();
        $doanThanhTra = DoanThanhTra::get();

        return view('admin.resources.thanhtrakiemtra.ketquathanhtratinh.index', ['danhmucs' => json_encode([
            'co_quan' => $coQuan,
            'doan_thanh_tra' => $doanThanhTra,
        ])]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function getKetQuaThanhTraTinh(Request $request)
    {
        $page = $request->get('page', 1);
        $per_page = $request->get('perPage', 5);
        $search = $request->get('search', null);
        $doan_thanh_tra_id = $request->get('doan_thanh_tra_id', null);
        $co_quan_ky_id = $request->get('co_quan_ky_id', null);
        $thoi_gian_ky_tu = $request->get('thoi_gian_ky_tu', null);
        $thoi_gian_ky_den = $request->get('thoi_gian_ky_den', null);

        $query = KetQuaThanhTraTinh::with(['doanThanhTra', 'tinhThanh']);
        if ($search != null) {
            $search = trim($search);
            $query = $query->where(function ($query) use ($search) {
                $query->where('so_van_ban', 'ilike', "%{$search}%");
            });
        }
        if ($doan_thanh_tra_id) {
            $query->where('doan_thanh_tra_id', $doan_thanh_tra_id);
        }
        if ($co_quan_ky_id) {
            $query->where('co_quan_ky_id', $co_quan_ky_id);
        }
        if ($thoi_gian_ky_tu) {
            $query->where('ngay_thong_bao', '>=', $thoi_gian_ky_tu);
        }
        if ($thoi_gian_ky_den) {
            $query->where('ngay_thong_bao', '<=', $thoi_gian_ky_den);
        }

        $query->orderBy('updated_at', 'DESC');
        $query->orderBy('id', 'DESC');
        $data = $query->paginate($per_page, ['*'], 'page', $page);
        return $data;
    }
    public function create()
    {
        $tinhThanh = Province::get();
        $doanThanhTra = DoanThanhTra::get();

        return view('admin.resources.thanhtrakiemtra.ketquathanhtratinh.add', ['danhmucs' => json_encode([
            'tinh_thanh' => $tinhThanh,
            'doan_thanh_tra' => $doanThanhTra,
        ])]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $info = $request->except('thanh_phan_doan');
        $validator = Validator::make($info, [
            'doan_thanh_tra_id' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'message' => __('Lỗi dữ liệu'),
            ], 422, []);
        }
        $ketqua = KetQuaThanhTraTinh::create($info);

        $ketqua->thanhPhanDoan()->createMany($request->thanh_phan_doan);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $tinhThanh = Province::get();
        $doanThanhTra = DoanThanhTra::get();

        return view('admin.resources.thanhtrakiemtra.ketquathanhtratinh.edit', ['danhmucs' => json_encode([

            'tinh_thanh' => $tinhThanh,
            'doan_thanh_tra' => $doanThanhTra,
        ]), 'ketquathanhtra' => KetQuaThanhTraTinh::where('id', $id)->with('doanThanhTra', 'tinhThanh', 'thanhPhanDoan')->firstOrFail()]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $info = $request->except('thanh_phan_doan');
        $validator = Validator::make($info, [
            'doan_thanh_tra_id' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'message' => __('Lỗi dữ liệu'),
            ], 422, []);
        }
        $ketqua = KetQuaThanhTraTinh::find($id);
        $ketqua->update($info);
        $ketqua->thanhPhanDoan()->delete();
        $ketqua->thanhPhanDoan()->createMany($request->thanh_phan_doan);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        KetQuaThanhTraTinh::find($id)->delete();
    }
}
