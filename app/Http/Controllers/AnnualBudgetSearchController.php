<?php

namespace App\Http\Controllers;

use App\District;
use App\KinhPhiHangNam;
use App\Lookup;
use App\ProtectedArea;
use Illuminate\Http\Request;

class AnnualBudgetSearchController extends Controller
{
    public function index()
    {
        return view('search.conservation-efforts.annual-budget.index');
    }

    public function getCategories(Request $request)
    {
        $categoryName = $request->get('name', null);
        $search = $request->get('search', '');
        $data = null;
        switch ($categoryName) {
            case 'quan_huyen':
                $data = District::select('id', 'name_vietnamese as name');
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
        $nguonKinhPhiIds = $request->get('nguon_kinh_phi_ids');
        $namThuNhaps = $request->get('nam_thu_nhap', [null, null]);
        $query = KinhPhiHangNam::query()->with('nguonKinhPhi', 'quanHuyen');

        if ($search) {
            $query->where(function ($query) use ($search) {
                $query->where('muc_dich_su_dung', 'ilike', "%{$search}%");
            });
        }

        if ($quanHuyenIds) {
            $query->whereIn('quan_huyen_id', $quanHuyenIds);
        }

        if ($nguonKinhPhiIds) {
            $query->whereIn('nguon_kinh_phi_id', $nguonKinhPhiIds);
        }

        if ($namThuNhaps[0]) {
            $query->where('thoi_gian', '>=', $namThuNhaps[0]);
        }

        if ($namThuNhaps[1]) {
            $query->where('thoi_gian', '<=', $namThuNhaps[1]);
        }

        return $query->paginate(10);
    }
    public function getDetail($id)
    {
        return view('search.conservation-efforts.annual-budget.detail',
            ['data' => KinhPhiHangNam::where('id', $id)->with('nguonKinhPhi', 'quanHuyen')->firstOrFail()]
        );
    }
}
