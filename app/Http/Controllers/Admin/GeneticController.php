<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\LoaiGen;
use App\Lookup;
use App\NguonGen;
use App\NguonGenNoiLuuGiu;
use App\NhomGen;
use App\NoiLuuGiu;
use App\Species;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

use function GuzzleHttp\json_encode;

class GeneticController extends Controller
{
    public function ViewIndex()
    {
        $phanLoai = Lookup::where('group', 'nhomnguongen')->select('id', 'name', 'code')->get();
        return view('admin.category.genetic.index', ['phanloai' => json_encode($phanLoai)]);
    }
    public function getNhomGen()
    {
        return NhomGen::with('phanLoai')->get();
    }
    public function addNhomGen(Request $request)
    {
        $data = $request->only('phan_loai_id', 'ten', 'mo_ta', 'ghi_chu');
        $validator = Validator::make($data, [
            'ten' => 'required|unique:nhom_gens',
            'phan_loai_id' => 'required',
        ], [
            'ten.unique' => 'Tên nhóm gen đã tồn tại'
        ]);
        if ($validator->fails()) {
            return response()->json([
                'message' => __('message.vaidator'),
                'error' => $validator->errors()
            ], 200, []);
        }
        try {
            NhomGen::create($data);
            $user = Auth::user();
            createLog($user->id, 'add_nhom_gen', $request->ip(), $request->header('User-Agent'), 'Thêm mới nhóm gen ' . $data['ten']);
            return response(['message' => 'Done'], 200);
        } catch (\Exception $e) {
            return response(['message' => 'Error'], 500);
        }
    }
    public function editNhomGen(Request $request)
    {
        $data = $request->only('id', 'phan_loai_id', 'ten', 'mo_ta', 'ghi_chu');
        $validator = Validator::make($data, [
            'ten' => 'required',
            'id' => 'required',
            'phan_loai_id' => 'required',
        ]);
        $trunglap = NhomGen::where('ten', $data['ten'])->where('id', '!=', $data['id'])->first();
        if ($trunglap) {
            return response()->json([
                'message' => __('Tên nhóm gen đã tồn tại'),
            ], 200, []);
        }
        if ($validator->fails()) {
            return response()->json([
                'message' => __('Lỗi dữ liệu'),
            ], 401, []);
        }
        try {
            NhomGen::find($data['id'])->update($data);
            $user = Auth::user();
            createLog($user->id, 'update_nhom_gen', $request->ip(), $request->header('User-Agent'), 'Cập nhật nhóm gen ' . $data['ten']);
            return response(['message' => 'Done'], 200);
        } catch (\Exception $e) {
            return response(['message' => 'Error'], 500);
        }
    }
    public function xoaNhomGen(Request $request, $id)
    {
        try {
            $info=NhomGen::where('id',$id)->first();
            NhomGen::find($id)->delete();
            $user = Auth::user();
            createLog($user->id, 'delete_nhom_gen', $request->ip(), $request->header('User-Agent'), 'Xóa nhóm gen '.$info['ten']);
            return response(['message' => 'Done'], 200);
        } catch (\Exception $e) {
            return response(['message' => 'Error'], 500);
        }
    }
    public function addLoaiGen(Request $request)
    {
        $data = $request->only('nhom_gen_id', 'ten', 'mo_ta', 'ghi_chu');
        $validator = Validator::make($data, [
            'ten' => 'required|unique:loai_gens',
            'nhom_gen_id' => 'required',
        ], [
            'ten.unique' => 'Tên loại gen đã tồn tại'
        ]);
        if ($validator->fails()) {
            return response()->json([
                'message' => __('message.vaidator'),
                'error' => $validator->errors()
            ], 200, []);
        }
        try {
            LoaiGen::create($data);
            $user = Auth::user();
            createLog($user->id, 'add_loai_gen', $request->ip(), $request->header('User-Agent'), 'Thêm mới loại gen ' . $data['ten']);
            return response(['message' => 'Done'], 200);
        } catch (\Exception $e) {
            return response(['message' => 'Error'], 500);
        }
    }
    public function editLoaiGen(Request $request)
    {
        $data = $request->only('nhom_gen_id', 'ten', 'mo_ta', 'ghi_chu', 'id');
        $validator = Validator::make($data, [
            'ten' => 'required',
            'nhom_gen_id' => 'required',
            'id' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'message' => __('Lỗi dữ liệu'),
            ], 401, []);
        }
        $trunglap = LoaiGen::where('ten', $data['ten'])->where('id', '!=', $data['id'])->first();
        if ($trunglap) {
            return response()->json([
                'message' => __('Tên nhóm gen đã tồn tại'),
            ], 200, []);
        }
        try {
            LoaiGen::find($data['id'])->update($data);
            $user = Auth::user();
            createLog($user->id, 'update_loai_gen', $request->ip(), $request->header('User-Agent'), 'Cập nhật loại gen ' . $data['ten']);
            return response(['message' => 'Done'], 200);
        } catch (\Exception $e) {
            return response(['message' => 'Error'], 500);
        }
    }
    public function getLoaiGen(Request $request)
    {
        $page = $request->get('page', 1);
        $per_pager = $request->get('perPage', 5);
        $search = $request->get('search', null);
        $nhom_gen_id = $request->get('nhom_gen_id', null);
        $query = LoaiGen::with('nhomGen');
        if ($search != null) {
            $search = trim($search);
            $query = $query->where(function ($query) use ($search) {
                $query->where('ten', 'ilike', "%{$search}%")
                    ->orWhere('mo_ta', 'ilike', "%{$search}%");
            });
            $query->orWhereHas('nhomGen', function ($query) use ($search) {
                $query->where('ten', 'ilike', "%{$search}%");
            });
        }
        if ($nhom_gen_id != null) {
            $query->where('nhom_gen_id', $nhom_gen_id);
        }
        $query->orderBy('updated_at', 'DESC');
        $query->orderBy('id', 'DESC');
        $data =  $query->paginate($per_pager, ['*'], 'page', $page);
        return $data;
    }
    public function xoaLoaiGen(Request $request, $id)
    {
        try {
            LoaiGen::find($id)->delete();
            $user = Auth::user();
            createLog($user->id, 'delete_loai_gen', $request->ip(), $request->header('User-Agent'), 'Xóa loại gen');
            return response(['message' => 'Done'], 200);
        } catch (\Exception $e) {
            return response(['message' => 'Error'], 500);
        }
    }
    public function showFormAdd()
    {
        $nhomGen = NhomGen::get();
        $loaiGen = LoaiGen::get();
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
        $triThuc = Lookup::where('group', 'nhomtrithuctruyenthong')->get();
        $data = [
            'nhom_gen' => $nhomGen,
            'loai_gen' =>  $loaiGen,
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
            'tri_thuc_truyen_thong' => $triThuc
        ];
        return view('admin.category.genetic.add', ['danhmucs' => json_encode($data)]);
    }
    public function searchLoai(Request $request)
    {
        $query = Species::query();
        $search = $request->get('search', null);
        $loai_id = $request->get('loai_id', null);
        if ($search) {
            $search = trim($search);
            $query->where('name', 'ilike', "%{$search}%")->orWhere('name_vietnamese', 'ilike', "%{$search}%");
        }
        if ($loai_id) {
            $query->orWhere('id', $loai_id);
        }
        return $query->select('id', 'name', 'name_vietnamese')->take(50)->get();
    }
    public function addNguonGen(Request $request)
    {
        $data = $request->only(
            "loai_gen_id",
            "nhom_gen_id",
            "ten",
            "ten_thong_thuong",
            "ten_dan_toc",
            "ten_khoa_hoc",
            'dac_diem',
            'su_dung_id',
            'gen_quy_hiem_id',
            'ma_so_quoc_gia',
            'ma_so_tinh',
            'tinh_trang_luu_giu_id',
            'tinh_trang_nghien_cuu_id',
            'tinh_trang_su_dung_id',
            'tri_thuc_id',
            'nguon_goc_viet_nam_id',
            'nguon_goc_dia_phuong_id',
            'nguon_goc_co_so_id',
            'xuat_xu',
            'phuong_thuc_luu_giu_id',
            'dien_tich_luu_giu',
            'vat_lieu_di_truyen_luu_giu',
            'so_luong_vat_lieu_di_truyen_luu_giu',
            'nam_bat_dau_luu_tru',
            'hinh_thuc_bao_quan',
            'tinh_trang_id',
            'bien_phap_bao_ton',
            'kha_nang_tiep_can',
            'quy_trinh_tiep_can',
            'gen_co_dieu_kien_id',
            'ghi_chu'
        );
        $noiLuuGiu = $request->get('noi_luu_giu', []);
        $validator = Validator::make($data, [
            'nhom_gen_id' => 'required',
            'ten' => 'unique:nguon_gens',

        ], [
            'ten.unique' => 'Tên loại gen đã tồn tại'
        ]);
        if ($validator->fails()) {
            return response()->json([
                'message' => __('message.vaidator'),
                'error' => $validator->errors()
            ], 200, []);
        }
        try {
            DB::beginTransaction();
            $data['tri_thuc_id'] = isset($data['tri_thuc_id']) && $data['tri_thuc_id'] ? json_encode($data['tri_thuc_id']) : null;
            $nguonGen = NguonGen::create($data);
            $user = Auth::user();
            createLog($user->id, 'add_nguon_gen', $request->ip(), $request->header('User-Agent'), 'Thêm mới nguồn gen ' . $data['ten']);
            if ($noiLuuGiu && count($noiLuuGiu)) {
                foreach ($noiLuuGiu as $item) {
                    NguonGenNoiLuuGiu::create([
                        'nguon_gen_id' => $nguonGen->id,
                        'noi_luu_giu_id' => $item
                    ]);
                   
                }
            }

            DB::commit();
            return response(['message' => 'Done'], 200);
        } catch (\Exception $e) {
            return response(['message' => 'Error'], 500);
        }
    }
    public function getNguonGen(Request $request)
    {
        $page = $request->get('page', 1);
        $per_pager = $request->get('perPage', 5);
        $search = $request->get('search', null);
        $nhom_gen_id = $request->get('nhom_gen_id', null);
        $loai_gen_id = $request->get('loai_gen_id', null);
        $gen_quy_hiem_id = $request->get('gen_quy_hiem_id', null);
        $khu_bao_ton_id = $request->get('khu_bao_ton_id', null);
        $co_so_bao_ton_id = $request->get('co_so_bao_ton_id', null);
        $query = NguonGen::with('nhomGen', 'loaiGen');
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
        if ($khu_bao_ton_id) {
            $noiLuuGiu = NoiLuuGiu::where('loai_doi_tuong', 'khu_bao_ton')->where('doi_tuong_id', $khu_bao_ton_id)->pluck('id')->toArray();
            $nguonGenIds = NguonGenNoiLuuGiu::whereIn('noi_luu_giu_id', $noiLuuGiu)->pluck('nguon_gen_id')->toArray();
            $query->whereIn('id', $nguonGenIds);
        }
        if ($co_so_bao_ton_id) {
            $noiLuuGiu = NoiLuuGiu::where('loai_doi_tuong', 'co_so_bao_ton')->where('doi_tuong_id', $co_so_bao_ton_id)->pluck('id')->toArray();
            $nguonGenIds = NguonGenNoiLuuGiu::whereIn('noi_luu_giu_id', $noiLuuGiu)->pluck('nguon_gen_id')->toArray();
            $query->whereIn('id', $nguonGenIds);
        }
        if ($nhom_gen_id) {
            $query->where('nhom_gen_id', $nhom_gen_id);
        }
        if ($loai_gen_id) {
            $query->where('loai_gen_id', $loai_gen_id);
        }
        if ($gen_quy_hiem_id) {
            $query->where('gen_quy_hiem_id', $gen_quy_hiem_id);
        }
        $query->orderBy('updated_at', 'DESC');
        $query->orderBy('id', 'DESC');
        $data =  $query->paginate($per_pager, ['*'], 'page', $page);
        return $data;
    }
    public function showFormEdit($id)
    {
        $nhomGen = NhomGen::get();
        $loaiGen = LoaiGen::get();
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
        $triThuc = Lookup::where('group', 'nhomtrithuctruyenthong')->get();
        $danhmucs = [
            'nhom_gen' => $nhomGen,
            'loai_gen' =>  $loaiGen,
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
            'tri_thuc_truyen_thong' => $triThuc
        ];
        $nguonGen = NguonGen::where('id', $id)->with(['nhomGen', 'species', 'loaiGen', 'suDung', 'genQuyHiem', 'tinhTrangLuuTru', 'noiLuuGius', 'tinhTrangNghienCuu', 'tinhTrangSuDung', 'nguonGocVietNam', 'nguonGocDiaPhuong', 'nguonGocCoSo', 'phuongThucLuuGiu', 'tinhTrang', 'genCoDieuKien'])->first();
        return view('admin.category.genetic.edit', ['danhmucs' => json_encode($danhmucs), 'data' => json_encode($nguonGen)]);
    }
    public function xoaNguonGen(Request $request, $id)
    {
        try {
            NguonGen::find($id)->delete();
            $user = Auth::user();
            createLog($user->id, 'delete_nguon_gen', $request->ip(), $request->header('User-Agent'), 'Xóa nguồn gen');
            return response(['message' => 'Success'], 200);
        } catch (\Exception $e) {
            return response(['message' => 'Error'], 500);
        }
    }
    public function getChiSoNguonGen()
    {
        $nhomGen = NhomGen::select('id', 'ten as name')->get();
        $loaiGen = LoaiGen::select('id', 'ten as name')->get();
        $genQuyHiem = Lookup::where('group', 'genquyhiem')->select('id', 'name')->get();
        foreach ($nhomGen as $item) {
            $item['so_luong'] = NguonGen::where('nhom_gen_id', $item['id'])->count();
        }
        foreach ($loaiGen as $item) {
            $item['so_luong'] = NguonGen::where('loai_gen_id', $item['id'])->count();
        }
        foreach ($genQuyHiem as $item) {
            $item['so_luong'] = NguonGen::where('gen_quy_hiem_id', $item['id'])->count();
        }
        return response(['nhom_gen' => $nhomGen, 'loai_gen' => $loaiGen, 'gen_quy_hiem' => $genQuyHiem], 200);
    }
    public function editNguonGen(Request $request)
    {
        $data = $request->only(
            "id",
            "loai_gen_id",
            "nhom_gen_id",
            "ten",
            "ten_thong_thuong",
            "ten_dan_toc",
            "ten_khoa_hoc",
            'dac_diem',
            'su_dung_id',
            'gen_quy_hiem_id',
            'ma_so_quoc_gia',
            'ma_so_tinh',
            'tinh_trang_luu_giu_id',
            'tinh_trang_nghien_cuu_id',
            'tinh_trang_su_dung_id',
            'tri_thuc_id',
            'nguon_goc_viet_nam_id',
            'nguon_goc_dia_phuong_id',
            'nguon_goc_co_so_id',
            'xuat_xu',
            'phuong_thuc_luu_giu_id',
            'dien_tich_luu_giu',
            'vat_lieu_di_truyen_luu_giu',
            'so_luong_vat_lieu_di_truyen_luu_giu',
            'nam_bat_dau_luu_tru',
            'hinh_thuc_bao_quan',
            'tinh_trang_id',
            'bien_phap_bao_ton',
            'kha_nang_tiep_can',
            'quy_trinh_tiep_can',
            'gen_co_dieu_kien_id',
            'ghi_chu'
        );
        $validator = Validator::make($data, [
            'id' => 'required',
            'nhom_gen_id' => 'required',
        ]);
        $trunglap = LoaiGen::where('ten', $data['ten'])->where('id', '!=', $data['id'])->first();
        if ($trunglap) {
            return response()->json([
                'message' => __('Tên nguồn gen đã tồn tại'),
            ], 200, []);
        }
        $noiLuuGiu = $request->get('noi_luu_giu', []);
        if ($validator->fails()) {
            return response()->json([
                'message' => __('Lỗi dữ liệu'),
            ], 401, []);
        }
        try {
            $data['tri_thuc_id'] = isset($data['tri_thuc_id']) && $data['tri_thuc_id'] ? json_encode($data['tri_thuc_id']) : null;
            NguonGen::find($data['id'])->update($data);
            $user = Auth::user();
            createLog($user->id, 'update_nguon_gen', $request->ip(), $request->header('User-Agent'), 'Cập nhật nguồn gen ' .$data['ten']);
            NguonGenNoiLuuGiu::where('nguon_gen_id',  $data['id'])->delete();
            if ($noiLuuGiu && count($noiLuuGiu)) {
                foreach ($noiLuuGiu as $item) {
                    NguonGenNoiLuuGiu::create([
                        'nguon_gen_id' => $data['id'],
                        'noi_luu_giu_id' => $item
                    ]);
                }
            }
            return response(['message' => 'Done'], 200);
        } catch (\Exception $e) {
            return response(['message' => 'Error'], 500);
        }
    }

    public function searchNoiLuuGiu(Request $request)
    {
        $query = NoiLuuGiu::query();
        $search = $request->get('search', null);
        $noi_luu_tru_id = $request->get('noi_luu_tru_id', null);
        if ($search) {
            $search = trim($search);
            $query->where('ten', 'ilike', "%{$search}%");
        }
        if ($noi_luu_tru_id) {
            $query->orWhereIn('id', $noi_luu_tru_id);
        }
        return $query->select('id', 'ten')->take(50)->get();
    }
}
