<?php

use App\Lookup;
use App\NguonGen as AppNguonGen;
use App\NguonGenTriThucTruyenThong;
use App\NhomGen;
use App\TriThucTruyenThong;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class NguonGen extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $myfile = fopen("database/seeds/dataNguonGen.json", "r");
        $files = json_decode(fread($myfile, filesize("database/seeds/dataNguonGen.json")));
        $data = $files->data;
        DB::beginTransaction();
        foreach ($data as $item) {
            // dd($item);
            $item = (array) $item;
            if (isset($item['giong_cay_trong'])) {
                $nhomGenID = NhomGen::where('ten', 'Giống cây trồng')->first()->id;
            } else if (isset($item['giong_vat_nuoi'])) {
                $nhomGenID = NhomGen::where('ten', 'Giống vật nuôi')->first()->id;
            } else if (isset($item['dong_vat_hoang_da'])) {
                $nhomGenID = NhomGen::where('ten', 'Động vật hoang dã')->first()->id;
            } else if (isset($item['thuc_vat_hoang_da'])) {
                $nhomGenID = NhomGen::where('ten', 'Thực vật hoang dã')->first()->id;
            } else if (isset($item['thuy_san'])) {
                $nhomGenID = NhomGen::where('ten', 'Thủy Sản')->first()->id;
            } else if (isset($item['nam'])) {
                $nhomGenID = NhomGen::where('ten', 'Nấm')->first()->id;
            } else if (isset($item['vi_sinh_vat'])) {
                $nhomGenID = NhomGen::where('ten', 'Vi sinh vật')->first()->id;
            }
            $nhomGen=App\NguonGen::create([
                'nhom_gen_id'=>$nhomGenID,
                'ten'=>isset($item['ten_tieng_viet'])? $item['ten_tieng_viet'] : null,
                'ten_thong_thuong'=>isset($item['ten_tieng_viet'])? $item['ten_tieng_viet'] : null,
                'ten_khoa_hoc'=>isset($item['ten_khoa_hoc'])? $item['ten_khoa_hoc'] : null,
                'dac_diem'=>isset($item['mo_ta_dac_tinh_sinh_hoc'])? $item['mo_ta_dac_tinh_sinh_hoc']:null,
            ]);
            // dd($nhomGen);
            if(isset($item['gen_lttp'])){
                $nhomID=Lookup::where('code', 'luongthucthucpham')->first()->id;
            }else if(isset($item['gen_duoc_lieu'])){
                $nhomID=Lookup::where('code', 'duocpham')->first()->id;
            }else if(isset($item['gen_tam_linh'])){
                $nhomID=Lookup::where('code', 'tamlinh')->first()->id;
            }else if(isset($item['gen_khac'])){
                $nhomID=Lookup::where('code', 'nguongenkhac')->first()->id;
            }
            // dd(isset($item['ten_tieng_viet']) ? ($item['ten_tieng_viet'] ? $item['ten_tieng_viet'] : $item['ten_khoa_hoc']):$item['ten_khoa_hoc']);
            $triThucTruyenThong=TriThucTruyenThong::create([
                'ten'=>isset($item['ten_tieng_viet']) ? ($item['ten_tieng_viet'] ? $item['ten_tieng_viet'] : $item['ten_khoa_hoc']):$item['ten_khoa_hoc'],
                'nhom_id'=>$nhomID
            ]);
            NguonGenTriThucTruyenThong::create([
                'nguon_gen_id'=>$nhomGen->id,
                'tri_thuc_truyen_thong_id'=>$triThucTruyenThong->id
            ]);
        }
        DB::commit();
        echo "done";
    }
}
