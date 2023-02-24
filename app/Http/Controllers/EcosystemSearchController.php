<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\District;
use App\HeSinhThai;
use App\Lookup;
use App\ProtectedArea;
use DB;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB as FacadesDB;

class EcosystemSearchController extends Controller
{
    public function index()
    {
        return view('search.conservation-area.ecosystem.index');
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
                if ($request->has('top-suggest') && boolval($request->get('top-suggest'))) {
                    $data->whereNotNull('orig_name')
                        ->withCount(['heSinhThai' => function ($query) {
                            $query->whereNotNull('geom');
                        }])
                        ->orderBy('he_sinh_thai_count', 'desc');
                }
                break;
            default:
                $data = Lookup::where('group', $categoryName)
                    ->select('id', 'name');
                if ($request->has('top-suggest') && boolval($request->get('top-suggest'))) {
                    $data
                        ->withCount(['heSinhThai' => function ($query) {
                            $query->whereNotNull('geom');
                        }])
                        ->orderBy('he_sinh_thai_count', 'desc');
                }
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
        $query = HeSinhThai::query()->with('heSinhThai', 'diaDiem');
        $query = $this->setIndexFilterQuery($request, $query);
        return $query->paginate(10);
    }

    public function getMapData(Request $request)
    {
        $query = \App\HeSinhThai::where('geom', '<>', null)->with('heSinhThai')->select('id', 'ten', 'he_sinh_thai_lookup_id', 'geom');
        $query = $this->setIndexFilterQuery($request, $query);
        return response()->json([
            'data' => $query->get(),
            'legends' => Lookup::where('group', 'hesinhthai')->select('id', 'name')->get(),
        ], 200, []);
    }
    private function setIndexFilterQuery(Request $request, Builder $query)
    {
        $search = $request->get('search', '');
        $quanHuyenIds = $request->get('quan_huyen_ids');
        $heSinhThaiIds = $request->get('he_sinh_thai_ids');
        $khuBaoTonIds = $request->get('khu_bao_ton_ids');

        if ($search) {
            $query->where(function ($query) use ($search) {
                $query->where('ten', 'ilike', "%{$search}%");
            });
        }

        if ($quanHuyenIds) {
            $query->where(function ($query) use ($quanHuyenIds) {
                foreach ($quanHuyenIds as $quanHuyenId) {
                    $query->orWhereRaw(FacadesDB::raw("quan_huyen::jsonb @> '[" . $quanHuyenId . "]'"));
                }
            });
        }

        if ($khuBaoTonIds) {
            $query->whereIn('dia_diem_id', $khuBaoTonIds);
        }
        if ($heSinhThaiIds) {
            $query->whereIn('he_sinh_thai_lookup_id', $heSinhThaiIds);
        }
        return $query;
    }

    public function getDetail($id)
    {
        return view(
            'search.conservation-area.ecosystem.detail',
            ['data' => HeSinhThai::where('id', $id)->with(
                'heSinhThai',
                'diaDiem',
                'phanLoai',
                'nguonGoc',
                'phanLoaiRung',
                'phanLoaiHeSinhThai'
            )->firstOrFail()]
        );
    }
}
