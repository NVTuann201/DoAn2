<?php

namespace App\Http\Controllers;

use App\CoSoBaoTon;
use App\DatasetResource;
use App\ProtectedArea;
use Illuminate\Http\Request;

class AreaController extends Controller
{
    public function getTopPublishingOfArea()
    {
        $query = \App\ProtectedArea::query();
        $query->select('name', 'orig_name', 'id')
            ->has('datasetResourceAreas')
            ->withCount('datasetResourceAreas')
            ->orderBy('dataset_resource_areas_count', 'desc')
            ->limit(10);
        return response()->json([
            'message' => 'Thành công',
            'result' => $query->get(),
        ], 200, []);
    }

    public function index(Request $request)
    {
        $search = $request->get('search');
        $query = \App\ProtectedArea::query();
        if (!empty($search)) {
            $query->where(function ($query) use ($search) {
                $query->orWhere('name', 'ilike', "%{$search}%");
                $query->orWhere('orig_name', 'ilike', "%{$search}%");
            });
        }
        $query->select('name', 'orig_name', 'id');
        $query->orderBy('name');
        $query->orderBy('orig_name');

        return response()->json([
            'message' => 'Thành công',
            'result' => $query->get(),
        ], 200, []);
    }

    public function getDoiTuongKbt()
    {
        $khuBaoTon = ProtectedArea::select('name', 'orig_name', 'id')->where('status', 'Designated')->get();
        $coSoBaoTon = CoSoBaoTon::select('ten as name', 'id')->get();
        $khuDeXuat = ProtectedArea::select('name', 'orig_name', 'id')->where('status', 'Proposed')->get();
        return response([
            'khu_bao_ton' => $khuBaoTon,
            'co_so_bao_ton' => $coSoBaoTon,
            'khu_dang_de_xuat' => $khuDeXuat
        ], 200);
    }
}
