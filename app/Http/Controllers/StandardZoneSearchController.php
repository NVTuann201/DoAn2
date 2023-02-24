<?php

namespace App\Http\Controllers;

use App\District;
use App\Lookup;
use App\OTieuChuan;
use App\ProtectedArea;
use Illuminate\Http\Request;

class StandardZoneSearchController extends Controller
{
    public function index()
    {
        return view('search.conservation-area.standard-zone.index');
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

        $khuBaoTonIds = $request->get('khu_bao_ton_ids');

        $query = OTieuChuan::query()->with('diaDiem');
        if ($search) {
            $query->where(function ($query) use ($search) {
                $query->where('ten', 'ilike', "%{$search}%");
            });
        }

        if ($khuBaoTonIds) {
            $query->whereIn('dia_diem_id', $khuBaoTonIds);
        }
        return $query->paginate(10);
    }

    public function getMapData()
    {
        $query = \App\OTieuChuan::where('geom', '<>', null)->with('diaDiem')->select('id', 'ten', 'dia_diem_id', 'geom')->get();
        return response()->json([
            'data' => $query,
            'legends' => [],
        ], 200, []);
    }
}
