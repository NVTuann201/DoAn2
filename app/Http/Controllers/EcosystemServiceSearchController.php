<?php

namespace App\Http\Controllers;

use App\DichVuHeSinhThai;
use App\HeSinhThai;
use App\Lookup;
use Illuminate\Http\Request;

class EcosystemServiceSearchController extends Controller
{
    public function index()
    {
        return view('search.conservation-efforts.ecosystem-service.index');
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
            case 'he_sinh_thai':
                $data = [
                   [ 'id'=> 1, 'name'=> "Hệ sinh thái rừng thường xanh núi đất độ cao trên 600m" ],
                   [ 'id'=> 2, 'name'=> "Hệ sinh thái rừng thường xanh núi đất độ cao dưới 600m" ],
                   [ 'id'=> 3, 'name'=> "Hệ sinh thái rừng hỗn giao tre nứa xen cây gỗ" ],
                   [ 'id'=> 4, 'name'=> "Hệ sinh thái rừng trên núi đá vôi" ],
                   [ 'id'=> 5, 'name'=> "Hệ sinh thái tràng cỏ cây bụi" ],
                   [ 'id'=> 6, 'name'=> "Hệ sinh thái rừng trồng" ],
                   [ 'id'=> 7, 'name'=> "Hệ sinh thái khu dân cư đô thị" ],
                   [ 'id'=> 8, 'name'=> "Hệ sinh thái khu dân cư nông thôn" ],
                   [ 'id'=> 9, 'name'=> "Hệ sinh thái nông nghiệp" ],
                   [ 'id'=> 10, 'name'=> "Hệ sinh thái đất ngập nước" ],
                ];
                break;
            default:
                $data = Lookup::where('group', $categoryName)->select('id', 'name');
                break;
        }
        if ($search && $categoryName != 'he_sinh_thai') {
            $data->where(function ($query) use ($search,$categoryName) {
                $query->where($categoryName!=='he_sinh_thai'?'name':'ten', 'ilike', "%{$search}%");
            });
        }
        return ['result' =>$categoryName == 'he_sinh_thai' ? $data: $data->limit(10)->get()];
    }

    public function getSearchData(Request $request)
    {
        $search = $request->get('search', '');
        $phanLoaiIds = $request->get('phan_loai_ids');
        $heSinhThaiIds = $request->get('he_sinh_thai_ids');
        $query = DichVuHeSinhThai::query()->with('phanLoai','heSinhThai');

        if ($search) {
            $query->where(function ($query) use ($search) {
                $query->where('ten', 'ilike', "%{$search}%");
            });
        }

        if ($phanLoaiIds) {
            $query->whereIn('phan_loai_id', $phanLoaiIds);
        }

        if ($heSinhThaiIds) {
            $query->whereHas('heSinhThai',function ($query) use ($heSinhThaiIds){
                $query->whereIn('id',$heSinhThaiIds);
            });
        }

        return $query->paginate(10);
    }
}
