<?php

namespace App\Http\Controllers;

use App\CoSoBaoTon;
use App\DarwinCoreOccurrences;
use App\DarwinCoreTaxon;
use App\ProtectedArea;
use App\DatasetResource;
use App\District;
use App\HeSinhThai;
use App\KingDom;
use App\Lookup;
use App\NbdsTaxonExtension;
use App\NguonGen;
use App\NguonGenNoiLuuGiu;
use App\NguonGenTriThucTruyenThong;
use App\NhomGen;
use App\NoiLuuGiu;
use App\PhyLum;
use App\TriThucTruyenThong;
use Session;
use Illuminate\Support\Facades\Auth;
use DB;
use Illuminate\Support\Facades\Log;


class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function index()
    {
        return view('index', ['quan_huyen' => json_encode(District::all())]);
    }

    public function setLocale($lang)
    {
        \Session::put('locale', $lang);
        return response()->json([
            'code'    => 200,
            'message' => __('success'),
            'result'  => []
        ], 200, []);
    }

    public function infoHome()
    {
        $sobodulieu = DatasetResource::where('status', 'public')->pluck('id');
        $soloai = DarwinCoreTaxon::whereIn('dataset_resource_id', $sobodulieu)->groupBy('species_id')->pluck('species_id')->count();
        $solansuathien = DarwinCoreOccurrences::query()->whereHas('dataResource', function ($query) {
            $query->filterStatusPublic();
        })->count();
        $sobodulieu = DatasetResource::query()->filterStatusPublic()->count();

        return response()->json([
            'soloai' => $soloai,
            'sobodulieu' => $sobodulieu,
            'solansuathien' => $solansuathien,
            'lang' => Session::get('locale')
        ], 200, []);
    }

    public function checkLogin()
    {
        $username = null;
        if (Auth::guard()->check()) {
            $username = Auth::guard()->user()->name;
        }
        return response()->json([
            'name' => $username,
        ], 200, []);
    }

    public function getOptionByName($name)
    {
        return response()->json([
            'value' => DB::table('options')->where('name', $name)->value('value')
        ], 200, []);
    }

    public function getMenuData()
    {
        $khuBaoTon = ProtectedArea::select('id', 'name', 'orig_name', 'status')->where('status', 'Designated')->get();
        $khuDeXuat = ProtectedArea::select('id', 'name', 'orig_name', 'status')->where('status', 'Proposed')->get();
        $coSoBaoTon = CoSoBaoTon::select('id', 'ten')->where('status', 'Designated')->get();
        $heSinhThai = Lookup::where('group', 'hesinhthai')->get();
        $triThucTruyenThong = Lookup::where('group', 'nhomtrithuctruyenthong')->get();
        $nhomGen = Lookup::where('group', 'nhomnguongen')->select('id', 'name', 'code')->get();
        $nhomTriThuc = Lookup::where('group', 'nhomtrithuctruyenthong')->select('id', 'name')->get();
        $apluc = Lookup::where('group', 'phanCapBangTinTongHop')->get();
        $data = [
            'khu_bao_ton' => $khuBaoTon,
            'co_so_bao_ton' => $coSoBaoTon,
            'he_sinh_thai' => $heSinhThai,
            'tri_thuc_truyen_thong' => $triThucTruyenThong,
            'nhom_gen' => $nhomGen,
            'nhom_tri_thuc' => $nhomTriThuc,
            'ap_luc' => $apluc,
            'khu_de_xuat' => $khuDeXuat
        ];
        return response(['data' => $data], 200);
    }

    public function dashboard()
    {

        $triThucTruyenThong = Lookup::where('group', 'nhomtrithuctruyenthong')->get();
        foreach ($triThucTruyenThong as $item) {
            $item['so_luong'] = TriThucTruyenThong::where('nhom_id', $item['id'])->count();
        }
        $heSinhThai = Lookup::where('group', 'hesinhthai')->get();
        foreach ($heSinhThai as $item) {
            $item['dien_tich'] = HeSinhThai::where('he_sinh_thai_lookup_id', $item['id'])->sum('dien_tich');
        }
        $coSoBaoTon = Lookup::where('group', 'loaiHinhCoSoBaoTon')->select('id', 'name')->get();
        foreach ($coSoBaoTon as $item) {
            $item['so_luong'] = CoSoBaoTon::where('loai_hinh_id', $item['id'])->count();
        }
        $nhomGen = NhomGen::select('id', 'ten as name')->get();
        foreach ($nhomGen as $item) {
            $item['so_luong'] = NguonGen::where('nhom_gen_id', $item['id'])->count();
        }
        $genQuyHiem = Lookup::where('group', 'genquyhiem')->select('id', 'name')->get();
        foreach ($genQuyHiem as $item) {
            $item['so_luong'] = NguonGen::where('gen_quy_hiem_id', $item['id'])->count();
        }

        return view('dashboard.index');
    }

    public function bieuDoLoai()
    {
        $kingdom = 'SELECT kingdoms.name_vietnamese, count(*) from species, genus, families, orders, classes, phylums, kingdoms
            WHERE 
        species.genus_id = genus.id AND
        genus.family_id = families.id AND
        families.order_id = orders.id AND
        orders.class_id = classes.id AND
        classes.phylum_id = phylums.id AND
        phylums.kingdom_id = kingdoms.id GROUP BY kingdoms.name_vietnamese';

        $thucVat = 'SELECT phylums.name_vietnamese, count(*) from species, genus, families, orders, classes, phylums
            WHERE 
        species.genus_id = genus.id AND
        genus.family_id = families.id AND
        families.order_id = orders.id AND
        orders.class_id = classes.id AND
        classes.phylum_id = phylums.id AND
        phylums.kingdom_id = 2 GROUP BY phylums.name_vietnamese';
        $dataThucVat = PhyLum::where('kingdom_id', 2)->select('name', 'name_vietnamese')->get();
        foreach ($dataThucVat  as $item) {
            $item['count'] = 0;
            foreach (DB::select($thucVat) as $tv) {
                if ($item['name_vietnamese'] == $tv->name_vietnamese) {
                    $item['count'] = $tv->count;
                }
            }
        }
        $dongVat = 'SELECT phylums.name_vietnamese, count(*) from species, genus, families, orders, classes, phylums
            WHERE 
        species.genus_id = genus.id AND
        genus.family_id = families.id AND
        families.order_id = orders.id AND
        orders.class_id = classes.id AND
        classes.phylum_id = phylums.id AND
        phylums.kingdom_id = 1 GROUP BY phylums.name_vietnamese';
        $dataDongVat = PhyLum::where('kingdom_id', 1)->select('name', 'name_vietnamese')->get();
        foreach ($dataDongVat  as $item) {
            $item['count'] = 0;
            foreach (DB::select($dongVat) as $dv) {
                if ($item['name_vietnamese'] == $dv->name_vietnamese) {
                    $item['count'] = $dv->count;
                }
            }
        }
        // dd($dataDongVat,$dataThucVat);
        // $loaiHst = Lookup::where('group', 'hesinhthai')->select('id', 'name')->get();
        // dd($loaiHst);
        $loaiHsthai = [
            [
                'name' => 'Hệ sinh thái rừng thường xanh núi đất độ cao trên 600m',
                'count' => 0
            ],
            [
                'name' => 'Hệ sinh thái rừng thường xanh núi đất độ cao dưới 600m',
                'count' => 0
            ],
            [
                'name' => 'Hệ sinh thái rừng hỗn giao tre nứa xen cây gỗ',
                'count' => 0
            ],
            [
                'name' => 'Hệ sinh thái rừng trên núi đá vôi',
                'count' => 0
            ],
            [
                'name' => 'Hệ sinh thái tràng cỏ cây bụi',
                'count' => 0
            ],
            [
                'name' => 'Hệ sinh thái rừng trồng',
                'count' => 0
            ],
            [
                'name' => 'Hệ sinh thái khu dân cư đô thị',
                'count' => 0
            ],
            [
                'name' => 'Hệ sinh thái khu dân cư nông thôn',
                'count' => 0
            ],
            [
                'name' => 'Hệ sinh thái nông nghiệp',
                'count' => 0
            ],
            [
                'name' => 'Hệ sinh thái đất ngập nước',
                'count' => 0
            ],
        ];
        foreach ($loaiHsthai as $item) {
            // $boDuLieuTheoHST = DatasetResource::where('loai_doi_tuong', 'he_sinh_thai')->where('doi_tuong_id', $item['id'])->pluck('id')->toArray();
            // $item['count'] = 0;
            // if (count($boDuLieuTheoHST) > 0) {
            //     $item['count'] = DarwinCoreTaxon::whereIn('dataset_resource_id', $boDuLieuTheoHST)->count();
            // }
            $IDdarwin = NbdsTaxonExtension::where('habitat', 'ilike', '%' . $item['name'] . '%')->groupBy('darwin_core_taxon_id')->pluck('darwin_core_taxon_id')->toArray();
            $item['count'] = DarwinCoreTaxon::whereIn('id', $IDdarwin)->groupBy('species_id')->pluck('species_id')->count();
            $loaiHst[] = [
                'name'=>$item['name'], 
                'count'=>$item['count']
            ];
        }
        $quanHuyen =  District::where('province_id', 26)->select('id', 'name_vietnamese')->get();
        foreach ($quanHuyen as $item) {
            $boDuLieuQuanHuyen = DatasetResource::whereRaw(DB::raw("quan_huyen::jsonb @> '[" . $item['id'] . "]'"))->pluck('id')->toArray();
            $item['count'] = 0;
            if (count($boDuLieuQuanHuyen) > 0) {
                $item['count'] = DarwinCoreTaxon::whereIn('dataset_resource_id', $boDuLieuQuanHuyen)->count();
            }
        }

        $doiTuong = [
            ['name' => 'Khu bảo tồn', 'code' => 'khu_bao_ton'],
            ['name' => 'Cơ sở bảo tồn', 'code' => 'co_so_bao_ton'],
            ['name' => 'Hệ sinh thái', 'code' => 'he_sinh_thai'],
        ];
        foreach ($doiTuong as &$item) {
            $boDuLieuDoiTuong = DatasetResource::where('loai_doi_tuong', $item['code'])->pluck('id')->toArray();
            $item['count'] = 0;
            if (count($boDuLieuDoiTuong) > 0) {
                $item['count'] = DarwinCoreTaxon::whereIn('dataset_resource_id', $boDuLieuDoiTuong)->count();
            }
        }
        $triThucTruyenThong=Lookup::where('group', 'nhomtrithuctruyenthong')->select('id', 'name')->get();
        foreach ($triThucTruyenThong as $item){
            $item['count']=TriThucTruyenThong::where('nhom_id', $item['id'])->count();
        }
        $noiLuuuGius = NoiLuuGiu::select('id', 'ten as name')->get()->toArray();
        foreach($noiLuuuGius as &$item){
            $item['count'] = NguonGenNoiLuuGiu::where('noi_luu_giu_id', $item['id'])->count();
        }
        $data = [
            'gioi' => DB::select($kingdom),
            'thuc_vat' => $dataThucVat,
            'dong_vat' => $dataDongVat,
            'he_sinh_thai' => $loaiHst,
            'quan_huyen' => $quanHuyen,
            'doi_tuong' => $doiTuong,
            'tri_thuc_truyen_thong'=>$triThucTruyenThong,
            'noi_luu_giu' => $noiLuuuGius
        ];
        return view('search.bieudoloai.index', ['dulieubieudo' => json_encode($data)]);
    }
}
