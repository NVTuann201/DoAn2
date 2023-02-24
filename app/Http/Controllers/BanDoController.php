<?php

namespace App\Http\Controllers;

use App\CoSoBaoTon;
use App\District;
use App\HeSinhThai;
use App\ProtectedArea;
use DB;

class BanDoController extends Controller
{
    public function index()
    {
        if (!request()->ajax())
            return view('search.conservation-area.map.index', ['quan_huyen' => json_encode(District::all())]);
        $protectedAreas = ProtectedArea::select('id', 'orig_name as name', 'geometry', 'desig as type');
        $heSinhThais = HeSinhThai::select('id', 'geom', 'ten as name', 'he_sinh_thai_lookup_id')->with('heSinhThai:id,name');
        $coSoBaoTons = CoSoBaoTon::select('id', 'geom', 'ten as name', 'loai_hinh_id')->with('loaiHinh:id,name');

        $search = request()->get('search');
        $quanHuyen = request()->get('quan_huyen');
        if ($search) {
            $protectedAreas->where(function ($query) use ($search) {
                $query->where('orig_name', 'ilike', "%{$search}%");
            });
            $heSinhThais->where(function ($query) use ($search) {
                $query->where('ten', 'ilike', "%{$search}%");
            });
            $coSoBaoTons->where(function ($query) use ($search) {
                $query->where('ten', 'ilike', "%{$search}%");
            });
        }

        if ($quanHuyen) {
            $protectedAreas->where(function ($query) use ($quanHuyen) {
                foreach ($quanHuyen as $district_id) {
                    $query->orWhereRaw(DB::raw("quan_huyen::jsonb @> '[" . $district_id . "]'"));
                }
            });
            $heSinhThais->where(function ($query) use ($quanHuyen) {
                foreach ($quanHuyen as $district_id) {
                    $query->orWhereRaw(DB::raw("quan_huyen::jsonb @> '[" . $district_id . "]'"));
                }
            });
            $coSoBaoTons->whereIn('quan_huyen_id', $quanHuyen);
        }
        return [
            'protectedAreas' => $protectedAreas->get(),
            'heSinhThais' => $heSinhThais->get(),
            'coSoBaoTons' => $coSoBaoTons->get(),
        ];
    }

    public function showMap()
    {
        return view('search.conservation-area.map.index_v2');
    }
    public function showMapImage()
    {
        return view('search.conservation-area.map.index_v3');
    }
}
