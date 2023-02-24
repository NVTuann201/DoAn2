<?php

namespace App\Http\Controllers;

use App\HopTacQuocTe;
use App\Lookup;
use App\ProtectedArea;
use App\Province;
use Illuminate\Http\Request;

class InternationalCooperationSearchController extends Controller
{
    public function index()
    {
        return view('search.conservation-efforts.international-cooperation.index');
    }

    public function getCategories(Request $request)
    {
        $categoryName = $request->get('name', null);
        $search = $request->get('search', '');
        $data = null;
        switch ($categoryName) {
            case 'tinh_thanh':
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
        $tinhThanhIds = $request->get('tinh_thanh_ids');
        $danhNghiaIds = $request->get('danh_nghia_ids');
        $phanCapIds = $request->get('phan_cap_ids');
        $phanLoaiIds = $request->get('phan_loai_ids');
        $tinhChat = $request->get('tinh_chat');
        $namBanHanh = $request->get('nam_ban_hanh', [null, null]);
        $query = HopTacQuocTe::query()->with([
            'phanCap',
            'phanLoai',
            'danhNghia']
        );

        if ($search) {
            $query->where(function ($query) use ($search) {
                $query->where('ten', 'ilike', "%{$search}%");
            });
        }

        if ($tinhThanhIds) {
            $query->whereIn('tinh_thanh_id', $tinhThanhIds);
        }

        if ($danhNghiaIds) {
            $query->whereIn('danh_nghia_id', $danhNghiaIds);
        }

        if ($phanCapIds) {
            $query->whereIn('phan_cap_id', $phanCapIds);
        }

        if ($phanLoaiIds) {
            $query->whereIn('phan_loai_id', $phanLoaiIds);
        }
        if ($tinhChat) {
            $query->where('tinh_chat', $tinhChat[0]);
        }

        if ($namBanHanh[0]) {
            $query->whereYear('ngay_ban_hanh', '>=', $namBanHanh[0]);
        }

        if ($namBanHanh[1]) {
            $query->whereYear('ngay_ban_hanh', '<=', $namBanHanh[1]);
        }

        return $query->paginate(10);
    }
    public function getDetail($id)
    {
        return view(
            'search.conservation-efforts.international-cooperation.detail',
            ['data' => HopTacQuocTe::where('id', $id)->with(
                [
                    'phanCap',
                    'phanLoai',
                    'danhNghia'
                ]
            )->firstOrFail()]
        );
    }
}
