<?php

namespace App\Http\Controllers;

use App\DichVuHeSinhThai;
use App\HeSinhThai;
use App\Lookup;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class HeSinhThaiController extends Controller
{
    public function viewSearchEcosystem()
    {
        $hst = Lookup::where('group', 'hesinhthai')->get();
        $phanLoai = Lookup::whereIn('group', ['phanloaihesinhthainuoc', 'phanloairunghesinhthaican'])->get();

        return view('search.ecosystem.ecosystem', [
            'lang' => Session::get('locale'),
            'hesinhthais' => json_encode($hst),
            'phanloais' => json_encode($phanLoai),
        ]);
    }
    
    public function viewSearchEcosystemData()
    {
        $hst = Lookup::where('group', 'hesinhthai')->get();
        $phanLoai = Lookup::whereIn('group', ['phanloaihesinhthainuoc', 'phanloairunghesinhthaican'])->get();

        return view('search.ecosystem.ecosystemdata', [
            'lang' => Session::get('locale'),
            'hesinhthais' => json_encode($hst),
            'phanloais' => json_encode($phanLoai),
        ]);
    }
    public function viewSearchService()
    {
        $phanLoai = Lookup::where('group', 'phanloaidichvuhesinhthai')->get();
        return view('search.ecosystem.service', [
            'lang' => Session::get('locale'),
            'phanloais' => json_encode($phanLoai),
        ]);
    }

    public function getHeSinhThai(Request $request)
    {
        $page = $request->get('page', 1);
        $per_pager = $request->get('perPage', 10);
        $search = $request->get('search', null);
        $he_sinh_thai = $request->get('he_sinh_thai', null);
        $phan_loai = $request->get('phan_loai', null);
        $khu_bao_ton_id = $request->get('khu_bao_ton_id', null);
        $query = HeSinhThai::with(['heSinhThai', 'diaDiem', 'phanLoai', 'nguonGoc']);
        if ($search != null) {
            $search = trim($search);
            $query = $query->where(function ($query) use ($search) {
                $query->where('ten', 'ilike', "%{$search}%")
                    ->orWhere('nguon_tai_lieu', 'ilike', "%{$search}%")
                    ->orWhere('mo_ta', 'ilike', "%{$search}%");
            });
        }
        if ($he_sinh_thai) {
            $query->whereIn('he_sinh_thai_lookup_id', $he_sinh_thai);
        }
        if($khu_bao_ton_id){
            $query = $query->where('dia_diem_id', $khu_bao_ton_id);
        }
        if ($phan_loai) {
            $query->whereIn('phan_loai_id', $phan_loai)
                ->orWhereIn('phan_loai_he_sinh_thai_id', $phan_loai);
        }

        $query->orderBy('updated_at', 'DESC');
        $query->orderBy('id', 'DESC');
        $data = $query->paginate($per_pager, ['*'], 'page', $page);
        return $data;
    }

    public function getDichVuHeSinhThai(Request $request)
    {
        $page = $request->get('page', 1);
        $per_pager = $request->get('perPage', 10);
        $search = $request->get('search', null);
        $khu_bao_ton = $request->get('khu_bao_ton', null);
        $luong_gia_tu = $request->get('luong_gia_tu', null);
        $luong_gia_den = $request->get('luong_gia_den', null);
        $query = DichVuHeSinhThai::with(['heSinhThai', 'phanLoai', 'heSinhThai.diaDiem']);
        if ($search != null) {
            $search = trim($search);
            $query = $query->where(function ($query) use ($search) {
                $query->where('ten', 'ilike', "%{$search}%")
                    ->orWhere('ghi_chu', 'ilike', "%{$search}%")
                    ->orWhere('mo_ta', 'ilike', "%{$search}%");
            });
        }
        if ($khu_bao_ton) {
            $query->whereHas('heSinhThai', function ($query) use ($khu_bao_ton) {
                $query->whereIn('dia_diem_id', $khu_bao_ton);
            });
        }
        if ($luong_gia_tu) {
            $query->where('gia_tri_luong_gia', '>=', $luong_gia_tu);
        }
        if ($luong_gia_den) {
            $query->where('gia_tri_luong_gia', '<=', $luong_gia_den);
        }

        $query->orderBy('updated_at', 'DESC');
        $query->orderBy('id', 'DESC');
        $data = $query->paginate($per_pager, ['*'], 'page', $page);
        return $data;
    }
}
