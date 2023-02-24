<?php

namespace App\Http\Controllers;

use App\Lookup;
use App\MoHinhSangKien;
use App\ProtectedArea;
use App\Province;
use Illuminate\Http\Request;

class BiodiversityConservationInitiativeSearchController extends Controller
{
    public function index()
    {
        return view('search.conservation-efforts.biodiversity-conservation-initiative.index');
    }

    public function getCategories(Request $request)
    {
        $categoryName = $request->get('name', null);
        $search = $request->get('search', '');
        $data = null;
        switch ($categoryName) {
            case 'quan_huyen':
                $data = Province::select('id', 'name_vietnamese as name');
                break;
            case 'khu_bao_ton':
                $data = ProtectedArea::select('id', 'orig_name as name');
                break;
            default:
                $data = Lookup::where('group', $categoryName)->select('id', 'name');
                break;
        }
        if ($search) {
            $data->where(function ($query) use ($search) {
                $query->where('name', 'ilike', "%{$search}%");
            });
        }
        return ['result' => $data->limit(10)->get()];
    }

    public function getSearchData(Request $request)
    {
        $search = $request->get('search', '');
        $quanHuyenIds = $request->get('quan_huyen_ids');
        $phanLoaiIds = $request->get('phan_loai_ids');
        $hinhThucIds = $request->get('hinh_thuc_ids');
        $nam = $request->get('nam_thuc_hien', [null, null]);
        $query = MoHinhSangKien::query()->with('phanLoai', 'hinhThuc', 'quanHuyen');

        if ($search) {
            $query->where(function ($query) use ($search) {
                $query->where('ten', 'ilike', "%{$search}%");
            });
        }

        if ($quanHuyenIds) {
            $query->whereIn('quan_huyen_id', $quanHuyenIds);
        }
        if ($phanLoaiIds) {
            $query->whereIn('phan_loai_id', $phanLoaiIds);
        }
        if ($hinhThucIds) {
            $query->whereIn('hinh_thuc_id', $hinhThucIds);
        }

        if ($nam[0]) {
            $query->whereYear('nam_thuc_hien', '>=', $nam[0]);
        }

        if ($nam[1]) {
            $query->whereYear('nam_thuc_hien', '<=', $nam[1]);
        }

        return $query->paginate(10);
    }
    public function getDetail($id)
    {
        return view('search.conservation-efforts.biodiversity-conservation-initiative.detail',
            ['data' => MoHinhSangKien::where('id', $id)->with('nguonKinhPhi', 'quanHuyen')->firstOrFail()]
        );
    }
}
