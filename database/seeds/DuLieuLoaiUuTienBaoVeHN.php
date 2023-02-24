<?php

use App\DarwinCoreTaxon;
use App\NbdsTaxonExtension;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DuLieuLoaiUuTienBaoVeHN extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $myfile = fopen("database/seeds/dataUuTienBaoVeHN.json", "r");
        $files = json_decode(fread($myfile, filesize("database/seeds/dataUuTienBaoVeHN.json")));
        $data = $files->data;
        DB::beginTransaction();
        DB::table('nbds_taxon_extensions')->update([
            'cites' => null,
            'nghi_dinh' => null,
            'phu_luc_nghi_dinh' => null,
            'red_list' => null,
            'iucn_2012' => null
        ]);
        foreach ($data as $item) {
            $item = (array)$item;
            if(isset($item['nguon_du_lieu']) && $item['nguon_du_lieu']=='2015'){
                $darwinCoreTaxon = DarwinCoreTaxon::where('scientific_name', $item['ten_khoa_hoc'])->where('dataset_resource_id',390)->first();
            }
            else if(isset($item['nguon_du_lieu']) && $item['nguon_du_lieu']=='2020'){
                $darwinCoreTaxon = DarwinCoreTaxon::where('scientific_name', $item['ten_khoa_hoc'])->where('dataset_resource_id',391)->first();
            }
            else if(isset($item['nguon_du_lieu']) && $item['nguon_du_lieu']=='2022'){
                $darwinCoreTaxon = DarwinCoreTaxon::where('scientific_name', $item['ten_khoa_hoc'])->where('dataset_resource_id',392)->first();
            }
            else {
                $darwinCoreTaxon = DarwinCoreTaxon::where('scientific_name', $item['ten_khoa_hoc'])->first();
            }
            NbdsTaxonExtension::where('darwin_core_taxon_id', $darwinCoreTaxon['id'])->update([
                'cites' => (isset($item['CITES']) ? $item['CITES'] : null),
                'nghi_dinh' => (isset($item['ND84'])) ? 84 : (isset($item['ND64']) ? 64 : null),
                'phu_luc_nghi_dinh' => (isset($item['ND84'])) ? $item['ND84'] : (isset($item['ND64']) ? $item['ND64'] : null),
                'red_list' => isset($item['sach_do_Viet_nam_27']) ? $item['sach_do_Viet_nam_27'] : null,
                'iucn_2012' => isset($item['IUCN_212']) ? $item['IUCN_212'] : null,
            ]);
        }
        DB::commit();
        echo "done";
    }
}
