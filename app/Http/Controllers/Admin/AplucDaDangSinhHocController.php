<?php

namespace App\Http\Controllers\Admin;

use App\BaoCaoApLuc;
use App\ChiThi;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Lookup;
use Illuminate\Support\Facades\Session;

class AplucDaDangSinhHocController extends Controller
{
    public function viewSearch(){
        $phanCap = Lookup::where('group', 'phanCapBangTinTongHop')->get();
        foreach($phanCap as $item){
          $soLuong =  BaoCaoApLuc::where('phan_cap_id', $item['id'])->count();
          $item['so_luong'] = $soLuong;
        }
        $chiThi = ChiThi::get();
        foreach($chiThi as $item){
            $soLuong =  BaoCaoApLuc::where('chi_thi_id', $item['id'])->count();
            $item['so_luong'] = $soLuong;
          }
        $data = [
            'phan_cap' => $phanCap,
            'chi_thi' => $chiThi
        ];
        return view('search.apluc.index', [
            'lang' => Session::get('locale'),
            'searchdata' => json_encode($data)
        ]);
    }

    public function index(Request $request){
        $page = $request->get('page', 1);
        $per_pager = $request->get('perPage', 5);
        $search = $request->get('search', null);
        $query = BaoCaoApLuc::with(['chiThi', 'phanCap']);
        $phan_cap_id =  $request->get('phan_cap_id', null);
        $chi_thi_id =  $request->get('chi_thi_id', null);
        if ($search != null) {
            $search = trim($search);
            $query = $query->where(function ($query) use ($search) {
                $query->where('mo_ta', 'ilike', "%{$search}%")
                    ->orWhere('don_vi_bao_cao', 'ilike', "%{$search}%")
                    ->orWhere('nguon_du_lieu', 'ilike', "%{$search}%")
                    ->orWhere('noi_phan_bo', 'ilike', "%{$search}%");
            });
        }
        if ($phan_cap_id) {
            $query->where('phan_cap_id', $phan_cap_id);
        }
        if ($chi_thi_id) {
            $query->where('chi_thi_id', $chi_thi_id);
        }
        $query->orderBy('updated_at', 'DESC');
        $query->orderBy('id', 'DESC');
        $data =  $query->paginate($per_pager, ['*'], 'page', $page);
        return $data;
    }
}
