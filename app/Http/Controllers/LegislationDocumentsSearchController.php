<?php

namespace App\Http\Controllers;

use App\Lookup;
use App\ProtectedArea;
use App\Province;
use App\VanBanPhapLuat;
use Illuminate\Http\Request;

class LegislationDocumentsSearchController extends Controller
{
    public function index()
    {
        return view('search.conservation-efforts.legislation-documents.index');
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

        $loaiIds = $request->get('loai_ids');
        $coQuanBanHanhIds = $request->get('co_quan_ban_hanh_ids');
        $linhVucIds = $request->get('linh_vuc_ids');

        $namBanHanh = $request->get('nam_ban_hanh', [null, null]);
        $query = VanBanPhapLuat::query()->with('phanLoai',
            'linhVuc',
            'coQuanBanHanh'
        );

        if ($search) {
            $query->where(function ($query) use ($search) {
                $query->where('ten', 'ilike', "%{$search}%");
            });
        }

        if ($loaiIds) {
            $query->whereIn('tinh_thanh_id', $loaiIds);
        }

        if ($coQuanBanHanhIds) {
            $query->whereIn('danh_nghia_id', $coQuanBanHanhIds);
        }

        if ($linhVucIds) {
            $query->whereIn('phan_cap_id', $linhVucIds);
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
        return view('search.conservation-efforts.legislation-documents.detail',
            ['data' => VanBanPhapLuat::where('id', $id)->with('phanLoai',
            'linhVuc',
            'coQuanBanHanh')->firstOrFail()]
        );
    }
}
