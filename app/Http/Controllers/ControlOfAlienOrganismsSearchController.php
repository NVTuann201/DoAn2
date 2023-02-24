<?php

namespace App\Http\Controllers;

use App\BaoCaoApLuc;
use App\District;
use App\KiemSoatSinhVatNgoaiLai;
use App\Lookup;
use App\Media;
use App\ProtectedArea;
use App\SinhVatNgoaiLai;
use Session;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use function GuzzleHttp\json_decode;

class ControlOfAlienOrganismsSearchController extends Controller
{
    public function index()
    {
        return view('search.conservation-efforts.control-of-alien-organisms.index');
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
        $quanHuyenIds = $request->get('quan_huyen_ids');
        $nam = $request->get('nam', [null, null]);
        $query = KiemSoatSinhVatNgoaiLai::query()->with('quanHuyen');

        if ($search) {
            $query->where(function ($query) use ($search) {
                $query->where('ten', 'ilike', "%{$search}%");
            });
        }

        if ($quanHuyenIds) {
            $query->whereIn('quan_huyen_id', $quanHuyenIds);
        }

        if ($nam[0]) {
            $query->whereYear('thoi_gian', '>=', $nam[0]);
        }

        if ($nam[1]) {
            $query->whereYear('thoi_gian', '<=', $nam[1]);
        }

        return $query->paginate(10);
    }
    public function getDetail($id)
    {
        return view(
            'search.conservation-efforts.control-of-alien-organisms.detail',
            ['data' => SinhVatNgoaiLai::where('id', $id)->with('quanHuyen')->firstOrFail()]
        );
    }

    public function viewSearch()
    {
        $phanLoai =  Lookup::where('group', 'phanLoaiSinhVatNgoaiLai')->select('id', 'name')->get();
        $nguyCo = Lookup::where('group', 'nguyCoXamHai')->select('id', 'name')->get();

        foreach ($nguyCo as $item) {
            $item['so_luong'] = SinhVatNgoaiLai::where('nguy_co_id', $item['id'])->count();
        }
        foreach ($phanLoai as $item) {
            $item['so_luong'] = SinhVatNgoaiLai::where('phan_loai_id', $item['id'])->count();
        }
        $data = [
            'phan_loai' => $phanLoai,
            'nguy_co' => $nguyCo
        ];
        return view('search.sinhvatngoailai.index', ['searchdata' => json_encode($data)]);
    }

    public function getSinhVatNgoaiLaiSearch(Request $request)
    {
        $page = $request->get('page', 1);
        $per_pager = $request->get('perPage', 15);
        $search = $request->get('search', null);
        $query = SinhVatNgoaiLai::with(['phanLoai', 'nguyCo', 'diaDiem']);
        $phan_loai =  $request->get('phan_loai', null);
        $nguy_co =  $request->get('nguy_co', null);
        $quanHuyen = $request->get('quan_huyen', null);
        if ($search != null) {
            $search = trim($search);
            $query = $query->where(function ($query) use ($search) {
                $query->where('ten', 'ilike', "%{$search}%")
                    ->orWhere('ten_khoa_hoc', 'ilike', "%{$search}%")
                    ->orWhere('ghi_chu', 'ilike', "%{$search}%")
                    ->orWhere('noi_phan_bo', 'ilike', "%{$search}%");
            });
        }
        if ($phan_loai) {
            $query->whereIn('phan_loai_id', $phan_loai);
        }
        if ($nguy_co) {
            $query->whereIn('nguy_co_id', $nguy_co);
        }
        if ($quanHuyen) {
            $query->where(function ($q) use ($quanHuyen) {
                foreach ($quanHuyen as $quan_huyen_id) {
                    $q->orWhereRaw(DB::raw("quan_huyen::jsonb @> '[" . $quan_huyen_id . "]'"));
                }
            });
        }
        $query->orderBy('updated_at', 'DESC');
        $query->orderBy('id', 'DESC');
        $data =  $query->paginate($per_pager, ['*'], 'page', $page);
        foreach ($data as $item) {
            $item['list_quan_huyen'] = null;
            if ($item['quan_huyen'] && count(json_decode($item['quan_huyen']))) {
                $qh = json_decode($item['quan_huyen']);
                $item['list_quan_huyen'] = District::whereIn('id', $qh)->select('id', 'name', 'name_vietnamese')->get()->toArray();
            }
        };
        return $data;
    }

    public function getApLucSearch(Request $request)
    {
        $page = $request->get('page', 1);
        $per_pager = $request->get('perPage', 15);
        $search = $request->get('search', null);
        $phan_cap =  $request->get('phan_cap', null);
        $chi_thi =  $request->get('chi_thi', null);
        $bat_dau =  $request->get('bat_dau', null);
        $ket_thuc =  $request->get('ket_thuc', null);
        $query = BaoCaoApLuc::with(['phanCap', 'chiThi']);
        if($phan_cap){
            $query->whereIn('phan_cap_id', $phan_cap);
        }
        if($chi_thi){
            $query->whereIn('chi_thi_id', $chi_thi);
        }
        if($bat_dau){
            foreach($bat_dau as $item){
                $item = json_decode($item);
                $value = $item->value;
                switch ($item->option) {
                    case "between":
                        if (count($value) > 1) {
                            $query->where('bat_dau', '>=', $value[0]);
                            $query->where('bat_dau', '<=', $value[1]);
                        }
                        break;
                    case "is":
                        $query->where('bat_dau', '=', $value[0]);
                        break;
                    case "before_end_of":
                        $query->where('bat_dau', '<=', $value[0]);
                        break;
                    case "after_start_of":
                        $query->where('bat_dau', '>=', $value[0]);
                        break;
                    default:
                }
            }
        }
        if($ket_thuc){
            foreach($ket_thuc as $item){
                $item = json_decode($item);
                $value = $item->value;
                switch ($item->option) {
                    case "between":
                        if (count($value) > 1) {
                            $query->where('ket_thuc', '>=', $value[0]);
                            $query->where('ket_thuc', '<=', $value[1]);
                        }
                        break;
                    case "is":
                        $query->where('ket_thuc', '=', $value[0]);
                        break;
                    case "before_end_of":
                        $query->where('ket_thuc', '<=', $value[0]);
                        break;
                    case "after_start_of":
                        $query->where('ket_thuc', '>=', $value[0]);
                        break;
                    default:
                }
            }
        }
        // $query->orderBy('updated_at', 'DESC');
        $query->orderBy('id', 'DESC');
        $data =  $query->paginate($per_pager, ['*'], 'page', $page);
        return $data;
    }
    public function viewDetailApluc($id){
        $data = BaoCaoApLuc::with(['phanCap', 'chiThi'])->where('id', $id)->first();
        $files = [];
        $fileIds = json_decode($data['files']);
        if ($data['files'] && count($fileIds) > 0) {
            $files = DB::table('media')->whereIn('id', $fileIds)->select('id', 'name', 'disk', 'size')->get();
            $data['fileList'] = $files->toArray();
        }else $data['fileList'] = [];
        return view('search.apluc.detail', [
            'lang' => Session::get('locale'),
            'data' => $data,
        ]);
    }
}
