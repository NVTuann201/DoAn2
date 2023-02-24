<?php

namespace App\Http\Controllers;

use App\District;
use App\Lookup;
use App\NguonGen;
use App\NguonGenNoiLuuGiu;
use App\NhomGen;
use App\NoiLuuGiu;
use App\Province;
use App\TriThucTruyenThong;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Session;

class GeneticController extends Controller
{
    public function viewSearchResources()
    {
        $data = $this->getDataSearch();
        return view('search.genetic.resources.index', [
            'lang' => Session::get('locale'),
            'danhmucs' => json_encode($data)
        ]);
    }

    public function viewSearchKnowledge()
    {
        $nhom = Lookup::where('group', 'nhomtrithuctruyenthong')->get();
        return view('search.genetic.knowledge.index', [
            'lang' => Session::get('locale'),
            'nhomtrithucs' => json_encode($nhom)
        ]);
    }

    public function getData(Request $request)
    {
        $page = $request->get('page', 1);
        $per_pager = $request->get('perPage', 10);
        $search = $request->get('search', null);
        $nhom_gen = $request->get('nhom_gen', null);
        $gen_quy_hiem = $request->get('gen_quy_hiem', null);

        $quan_huyen = $request->get('quan_huyen', null);
        $noi_luu_giu = $request->get('noi_luu_giu', null);
        $tinh_trang_luu_giu = $request->get('tinh_trang_luu_giu', null);
        $tinh_trang_su_dung = $request->get('tinh_trang_su_dung', null);
        $tinh_trang = $request->get('tinh_trang', null);
        $isExport = $request->get('is_export', null);

        $query = NguonGen::with('nhomGen', 'loaiGen', 'noiLuuGius', 'noiLuuGiuBangTrungGian.noiLuuGius');
        if ($search != null) {
            $search = trim($search);
            $query = $query->where(function ($query) use ($search) {
                $query->where('ten', 'ilike', "%{$search}%")
                    ->orWhere('ten_dan_toc', 'ilike', "%{$search}%")
                    ->orWhere('ten_thong_thuong', 'ilike', "%{$search}%")
                    ->orWhere('ghi_chu', 'ilike', "%{$search}%")
                    ->orWhere('dac_diem', 'ilike', "%{$search}%")
                    ->orWhere('xuat_xu', 'ilike', "%{$search}%")
                    ->orWhere('ten_khoa_hoc', 'ilike', "%{$search}%");
            });
        }
        if ($nhom_gen) {
            $nhomGens = NhomGen::whereIn('phan_loai_id', $nhom_gen)->pluck('id')->toArray();
            $query->whereIn('nhom_gen_id', $nhomGens);
        }
        if ($gen_quy_hiem) {
            $query->whereIn('gen_quy_hiem_id', $gen_quy_hiem);
        }
        if ($tinh_trang_luu_giu) {
            $query->whereIn('tinh_trang_luu_giu_id', $tinh_trang_luu_giu);
        }
        if ($tinh_trang_su_dung) {
            $query->whereIn('tinh_trang_su_dung_id', $tinh_trang_su_dung);
        }
        if ($tinh_trang) {
            $query->whereIn('tinh_trang_id', $tinh_trang);
        }
        if ($noi_luu_giu) {
            $noiLuuGiu = NguonGenNoiLuuGiu::whereIn('noi_luu_giu_id', $noi_luu_giu)->pluck('nguon_gen_id')->toArray();
            $query->whereIn('id', $noiLuuGiu);
        }
        if ($quan_huyen && count($quan_huyen) > 0) {
            foreach ($quan_huyen as $quan_huyen_id) {
                $queryNoiLuTru = NoiLuuGiu::query();
                $queryNoiLuTru->where(function ($q) use ($quan_huyen) {
                    foreach ($quan_huyen as $quan_huyen_id) {
                        $q->orWhereRaw(DB::raw("quan_huyen::jsonb @> '[" . $quan_huyen_id . "]'"));
                    }
                });

                $noiLuuGiuQuanHuyen =  $queryNoiLuTru->pluck('id')->toArray();
            }
            $noiLuuGiu = NguonGenNoiLuuGiu::whereIn('noi_luu_giu_id', $noiLuuGiuQuanHuyen)->pluck('nguon_gen_id')->toArray();
            $query->whereIn('id', $noiLuuGiu);
        }
        // if($tinh_thanh){
        //     $query->whereHas('noiLuuGiuBangTrungGian.noiLuuGius', function($query) use ($tinh_thanh){
        //         foreach($tinh_thanh as $tinh){
        //             $query->whereJsonContains('tinh_thanh', $tinh);

        //         }
        //     });
        // }
        if ($isExport == 1) {
            return $this->export($query->get()->toArray());
        }
        $query->orderBy('updated_at', 'DESC');
        $query->orderBy('id', 'DESC');
        $data =  $query->paginate($per_pager, ['*'], 'page', $page);
        return $data;
    }
    public function export($dataGenetic)
    {
        return Excel::create('Filename', function ($excel) use ($dataGenetic) {
            $excel->sheet('genetic', function ($sheet) use ($dataGenetic) {
                $data = array_merge([
                    ["Dữ liệu đa dạng sinh học"], [
                        "Tên",
                        "Tên thông thường",
                        "Tên dân tộc",
                        "Tên khoa học",
                        "Đặc điểm",
                        "Nhóm gen",
                    ]
                ], array_map(function ($arr) {
                    return [
                        $arr["ten"],
                        $arr["ten_thong_thuong"],
                        $arr["ten_dan_toc"],
                        $arr["ten_khoa_hoc"],
                        $arr["dac_diem"],
                        $arr["nhom_gen"]['ten'],
                    ];
                }, $dataGenetic));
                $sheet->fromArray($data, null, 'A1', false, false);
                $sheet->mergeCells('A1:F1');
                $sheet->setAutoSize(true);
                $sheet->cell('A2:F2', function ($cell) {
                    $cell->setAlignment('center');
                    $cell->setFontWeight('bold');
                });
                $sheet->cell('A1', function ($cell) {
                    $cell->setAlignment('center');
                    $cell->setFontWeight('bold');
                });
            });
        })->export('xls');
    }

    public function getDataSearch()
    {
        $nhomGen = Lookup::where('group', 'nhomnguongen')->select('id', 'name', 'code')->get();
        $suDung = Lookup::where('group', 'sudungnguongen')->get();
        $genQuyHiem = Lookup::where('group', 'genquyhiem')->get();
        $tinhTrangLuuGiu = Lookup::where('group', 'tinhtrangluugiu')->get();
        $tinhTrangNghienCuu = Lookup::where('group', 'tinhtrangnghiencuu')->get();
        $tinhTrangSuDung = Lookup::where('group', 'tinhtrangsudung')->get();
        $nguonGoc = Lookup::where('group', 'nguongocgen')->get();
        $nguonGocCoSo = Lookup::where('group', 'nguongoccoso')->get();
        $phuongThucLuuGiu = Lookup::where('group', 'phuongthucluugiu')->get();
        $tinhTrangNguonGen = Lookup::where('group', 'tinhtrangnguongen')->get();
        $genCoDK = Lookup::where('group', 'gencodk')->get();
        $tinhTrang = Lookup::where('group', 'tinhtrangnguongen')->get();
        $data = [
            'nhom_gen' => $nhomGen,
            'su_dung' => $suDung,
            'gen_quy_hiem' => $genQuyHiem,
            'tinh_trang_luu_giu' => $tinhTrangLuuGiu,
            'tinh_trang_nghien_cuu' => $tinhTrangNghienCuu,
            'tinh_trang_su_dung' => $tinhTrangSuDung,
            'nguon_goc' => $nguonGoc,
            'nguon_goc_co_so' => $nguonGocCoSo,
            'phuong_thuc_luu_giu' => $phuongThucLuuGiu,
            'tinh_trang_nguon_gen' => $tinhTrangNguonGen,
            'gen_co_dieu_kien' => $genCoDK,
            'tinh_trang' => $tinhTrang
        ];
        return $data;
    }
    public function getNoiLuuGiu(Request $request)
    {
        $per_pager = $request->get('perPage', 10);
        $search = $request->get('search', null);
        $query = NoiLuuGiu::query();
        if ($search != null) {
            $search = trim($search);
            $query = $query->where(function ($query) use ($search) {
                $query->where('ten', 'ilike', "%{$search}%")
                    ->orWhere('thon_xa', 'ilike', "%{$search}%")
                    ->orWhere('nguoi_so_huu', 'ilike', "%{$search}%")
                    ->orWhere('thong_tin_lien_he', 'ilike', "%{$search}%")
                    ->orWhere('ghi_chu', 'ilike', "%{$search}%");
            });
        }

        $query->orderBy('updated_at', 'DESC');
        $query->orderBy('id', 'DESC');
        $data =  $query->take($per_pager)->get();
        return [
            "message" => "Thành công",
            "result" => $data
        ];
    }

    public function getTinhThanh(Request $request)
    {
        $per_pager = $request->get('perPage', 10);
        $search = $request->get('search', null);
        $query = Province::query();
        if ($search != null) {
            $search = trim($search);
            $query = $query->where(function ($query) use ($search) {
                $query->where('name', 'ilike', "%{$search}%")
                    ->orWhere('name_vietnamese', 'ilike', "%{$search}%");
            });
        }

        $query->orderBy('updated_at', 'DESC');
        $query->orderBy('id', 'DESC');
        $data =  $query->take($per_pager)->get();
        return [
            "message" => "Thành công",
            "result" => $data
        ];
    }
    public function getQuanHuyen(Request $request)
    {
        $per_pager = $request->get('perPage', 100);
        $search = $request->get('search', null);
        $query = District::select('id', 'name', 'name_vietnamese');
        if ($search != null) {
            $search = trim($search);
            $query = $query->where(function ($query) use ($search) {
                $query->where('name', 'ilike', "%{$search}%")
                    ->orWhere('name_vietnamese', 'ilike', "%{$search}%");
            });
        }

        $query->orderBy('updated_at', 'DESC');
        $query->orderBy('id', 'DESC');
        $data =  $query->take($per_pager)->get();
        return [
            "message" => "Thành công",
            "result" => $data
        ];
    }

    public function viewDetail($id)
    {
        $nguonGen = NguonGen::where('id', $id)->with(['nhomGen', 'species', 'loaiGen', 'suDung', 'genQuyHiem', 'tinhTrangLuuTru', 'noiLuuGius', 'tinhTrangNghienCuu', 'tinhTrangSuDung', 'nguonGocVietNam', 'nguonGocDiaPhuong', 'nguonGocCoSo', 'phuongThucLuuGiu', 'tinhTrang', 'genCoDieuKien'])->first();
        return view('search.genetic.resources.detail', ['data' => json_encode($nguonGen)]);
    }

    public function getTriThucTruyenThong(Request $request)
    {
        $page = $request->get('page', 1);
        $per_pager = $request->get('perPage', 5);
        $search = $request->get('search', null);
        $nhom = $request->get('nhom', null);
        $query = TriThucTruyenThong::with(['nhom', 'nguonGen']);
        if ($search != null) {
            $search = trim($search);
            $query = $query->where(function ($query) use ($search) {
                $query->where('ten', 'ilike', "%{$search}%")
                    ->orWhere('ghi_chu', 'ilike', "%{$search}%")
                    ->orWhere('cap_giay_chung_nhan', 'ilike', "%{$search}%")
                    ->orWhere('mo_ta', 'ilike', "%{$search}%");
            });
        }
        if ($nhom != null) {
            $query->whereIn('nhom_id', $nhom);
        }
        $query->orderBy('updated_at', 'DESC');
        $query->orderBy('id', 'DESC');
        $data =  $query->paginate($per_pager, ['*'], 'page', $page);
        return $data;
    }
}
