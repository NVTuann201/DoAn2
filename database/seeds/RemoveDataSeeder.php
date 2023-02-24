<?php

use App\CoSoBaoTon;
use App\DarwinCoreOccurrences;
use App\DarwinCoreSimpleImage;
use App\DatasetResourceArea;
use App\DatNgapNuoc;
use App\DiemQuanTrac;
use App\KetQuaQuanTrac;
use App\KhuDuTruSinhQuyen;
use App\OTieuChuan;
use App\ProtectedArea;
use App\VungChimQuanTrong;
use Illuminate\Database\Seeder;

class RemoveDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
       $data = ProtectedArea::where('sub_loc', '<>', 'Hà Nội')->get();
        foreach($data as $item){
            KhuDuTruSinhQuyen::where('khu_bao_ton_id', $item->id)->delete();
            DatNgapNuoc::where('khu_bao_ton_id', $item->id)->delete();
            VungChimQuanTrong::where('khu_bao_ton_id', $item->id)->delete();
            DatNgapNuoc::where('khu_bao_ton_id', $item->id)->delete();
            CoSoBaoTon::where('dia_diem_id', $item->id)->delete();
            OTieuChuan::where('dia_diem_id', $item->id)->delete();

            $diemQuanTracIds = DiemQuanTrac::where('khu_bao_ton_id', $item->id)->pluck('id')->toArray();
            KetQuaQuanTrac::whereIn('diem_quan_trac_id', $diemQuanTracIds)->delete();
            DiemQuanTrac::where('khu_bao_ton_id', $item->id)->delete();

            $DarwinCoreOccurrencesId =  DarwinCoreOccurrences::where('protected_area_id', $item->id)->pluck('id')->toArray();
            DatasetResourceArea::where('protected_area_id', $item->id)->delete();
            DarwinCoreSimpleImage::whereIn('darwin_core_occurrence_id', $DarwinCoreOccurrencesId)->delete();
            DarwinCoreOccurrences::where('protected_area_id', $item->id)->delete();
        }
        ProtectedArea::where('sub_loc', '<>', 'Hà Nội')->delete();
        echo 'seed done';
        return;
    }
}
