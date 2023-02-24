<?php

namespace App\Http\Controllers;

use App\CoSoBaoTon;
use App\District;
use App\Lookup;
use App\ProtectedArea;
use Illuminate\Http\Request;

class ConservationInfrastructureSearchController extends Controller
{
    public function index()
    {
        return view('search.conservation-area.conservation-infrastructure.index');
    }

    public function getCategories(Request $request)
    {
        $categoryName = $request->get('name', null);
        $search = removeVietnameseAccents($request->get('search', ''));
        $data = null;
        switch ($categoryName) {
            case 'quan_huyen':
                $data = District::select('id', 'name_vietnamese as name');
                break;
            case 'khu_bao_ton':
                $data = ProtectedArea::select('id', 'orig_name', 'name');
                break;
            case 'trang_thai':
                    $data = [['code' => 'Designated','id' => 'Designated', 'name' => 'Được công nhận'], ['code' => 'Proposed', 'id' => 'Proposed','name' => 'Đang đề xuất']];
                    break;
            default:
                $data = Lookup::where('group', $categoryName)->select('id', 'name');
                break;
        }
        if ($search && $categoryName!='trang_thai') {
            $data->where(function ($query) use ($search) {
                $query->where('name', 'ilike', "%{$search}%");
            });
        }
        return ['result' => $categoryName=='trang_thai' ? $data :$data->limit(10)->get()];
    }

    public function getSearchData(Request $request)
    {
        $search = $request->get('search', '');
        $quanHuyenIds = $request->get('quan_huyen_ids');
        $loaiHinhIds = $request->get('loai_hinh_ids');
        $loaiHinhToChucIds = $request->get('loai_hinh_to_chuc_ids');
        $phanCapQuanLyIds = $request->get('phan_cap_quan_ly_ids');
        $khuBaoTonIds = $request->get('khu_bao_ton_ids');
        $trang_thai = $request->get('trang_thai', null);
        $query = CoSoBaoTon::query()->with('loaiHinh', 'loaiHinhToChuc', 'quanLy', 'quanHuyen', 'diaDiem');
        if ($search) {
            $query->where(function ($query) use ($search) {
                $query->where('ten', 'ilike', "%{$search}%");
            });
        }
        if ($quanHuyenIds) {
            $query->whereIn('quan_huyen_id', $quanHuyenIds);
        }
        if ($loaiHinhIds) {
            $query->whereIn('loai_hinh_id', $loaiHinhIds);
        }
        if ($loaiHinhToChucIds) {
            $query->whereIn('loai_hinh_to_chuc_id', $loaiHinhToChucIds);
        }
        if ($phanCapQuanLyIds) {
            $query->whereIn('quan_ly_id', $phanCapQuanLyIds);
        }
        if ($khuBaoTonIds) {
            $query->whereIn('dia_diem_id', $khuBaoTonIds);
        }
        if ($trang_thai) {
            $query->whereIn('status', $trang_thai);
        }
        return $query->paginate(10);
    }

    public function getMapData()
    {
        $query = \App\CoSoBaoTon::where('geom', '<>', null)->with('loaiHinh')->select('id', 'ten', 'loai_hinh_id', 'geom')->get();
        return response()->json([
            'data' => $query,
            'legends' => Lookup::where('group', 'loaiHinhCoSoBaoTon')->select('id', 'name')->get(),
        ], 200, []);
    }

    public function getDetail($id)
    {
        return view('search.conservation-area.conservation-infrastructure.detail',
            ['data' => CoSoBaoTon::where('id', $id)->with('loaiHinh', 'loaiHinhToChuc', 'quanLy', 'tinhThanh', 'diaDiem')->firstOrFail()]
        );
    }
}
