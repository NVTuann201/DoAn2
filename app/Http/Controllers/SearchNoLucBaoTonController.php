<?php

namespace App\Http\Controllers;

use App\DieuUocQuocTe;
use DB;
use Illuminate\Http\Request;

class SearchNoLucBaoTonController extends Controller
{
    public function indexDieuUocQuocTe()
    {
        return view('search.nolucbaoton.dieuuocquocte');
    }
    public function searchDieuUocQuocTe(Request $request)
    {
        $page = $request->get('page', 1);
        $per_pager = $request->get('perPage', 5);
        $search = $request->get('search', null);
        $nam_ban_hanh = $request->get('nam_ban_hanh', [null, null]);
        $nam_viet_nam_tham_gia = $request->get('nam_viet_nam_tham_gia', [null, null]);
        $nam_co_hieu_luc = $request->get('nam_co_hieu_luc', [null, null]);
        $hieu_luc = $request->get('hieu_luc', null);
        $query = DieuUocQuocTe::query();
        if ($search != null) {
            $search = trim($search);
            $query = $query->where(function ($query) use ($search) {
                $query->where('ten', 'ilike', "%{$search}%")
                    ->orWhere('noi_dung_chinh', 'ilike', "%{$search}%")
                    ->orWhere('noi_dung_toan_van', 'ilike', "%{$search}%")
                    ->orWhere('ghi_chu', 'ilike', "%{$search}%");
            });
        }

        if ($nam_ban_hanh[0]) {
            $query->whereYear('ngay_ban_hanh', '>=', $nam_ban_hanh[0]);
        }
        if ($nam_ban_hanh[1]) {
            $query->whereYear('ngay_ban_hanh', '<=', $nam_ban_hanh[1]);
        }
        if ($nam_viet_nam_tham_gia[0]) {
            $query->whereYear('ngay_viet_nam_tham_gia', '>=', $nam_viet_nam_tham_gia[0]);
        }
        if ($nam_viet_nam_tham_gia[1]) {
            $query->whereYear('ngay_viet_nam_tham_gia', '<=', $nam_viet_nam_tham_gia[1]);
        }
        if ($nam_co_hieu_luc[0]) {
            $query->whereYear('ngay_hieu_luc', '>=', $nam_co_hieu_luc[0]);
        }
        if ($nam_co_hieu_luc[1]) {
            $query->whereYear('ngay_hieu_luc', '<=', $nam_co_hieu_luc[1]);
        }

        if ($hieu_luc != null) {
            $query->where('hieu_luc', $hieu_luc);
        }
        $query->orderBy('updated_at', 'DESC');
        $query->orderBy('id', 'DESC');
        $data = $query->paginate($per_pager, ['*'], 'page', $page);
        foreach ($data as $item) {
            $files = [];
            $fileIds = json_decode($item['files']);
            if ($item['files'] && count($fileIds) > 0) {
                $files = DB::table('media')->whereIn('id', $fileIds)->select('id', 'name', 'disk', 'size')->get();
                $item['fileList'] = $files->toArray();
            } else {
                $item['fileList'] = [];
            }

        }
        return $data;
    }

    public function detailDieuUocQuocTe($id)
    {
        $data = DieuUocQuocTe::find($id);
        $fileIds = json_decode($data['files']);
        if ($data['files'] && count($fileIds) > 0) {
            $files = DB::table('media')->whereIn('id', $fileIds)->select('id', 'name', 'disk', 'size')->get();
            $data['files'] = $files->toArray();
        } else {
            $data['files'] = [];
        }

        return view('search.nolucbaoton.detail-dieuuocquocte', ['data' => $data]);
    }
}
