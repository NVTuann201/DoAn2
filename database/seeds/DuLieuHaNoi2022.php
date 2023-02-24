<?php

use App\Classes;
use App\DarwinCoreTaxon;
use App\DatasetResource;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Species;
use App\Genus;
use App\Family;
use App\KingDom;
use App\NbdsTaxonExtension;
use App\Order;
use App\PhyLum;

class DuLieuHaNoi2022 extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $myfile = fopen("database/seeds/data2022.json", "r");
        $files = json_decode(fread($myfile, filesize("database/seeds/data2022.json")));
        $data = $files->data;
        DB::beginTransaction();
        foreach ($data as $item) {
            $item = (array)$item;
            $species = Species::where('name', $item['ten_khoa_hoc'])->first();
            if ($species) {
                $genus = Genus::where('id', $species['genus_id'])->first();
            } else {
                $genus = Genus::where('name', $item['chi'])->first();
                if ($genus) {
                    $species = Species::create([
                        'name' => $item['ten_khoa_hoc'],
                        'name_vietnamese' => $item['ten_tieng_Viet'],
                        'genus_id' => $genus['id']
                    ]);
                }
            }
            if ($genus) {
                $family = Family::where('id', $genus['family_id'])->first();
                $order = Order::where('id', $family['order_id'])->first();
                $class = Classes::where('id', $order['class_id'])->first();
                $phylum = PhyLum::where('id', $class['phylum_id'])->first();
                $kingdom = KingDom::where('id', $phylum['kingdom_id'])->first();
                // $dataset_resource = DatasetResource::where('title', 'Dữ liệu Hà Nội 2015')->first();
                $darwinCoreTaxon = DarwinCoreTaxon::create([
                    'scientific_name' => $item['ten_khoa_hoc'],
                    'kingdom_id' => $kingdom['id'],
                    'phylum_id' => $phylum['id'],
                    'class_id' => $class['id'],
                    'order_id' => $order['id'],
                    'family_id' => $family['id'],
                    'genus_id' => $genus['id'],
                    'species_id' => $species['id'],
                    'scientific_name_authorship' => isset($item['tac_gia']) ? $item['tac_gia'] : null,
                    'references' => isset($item['nguon_tai_lieu_tham_khao']) ? $item['nguon_tai_lieu_tham_khao'] : null,
                    'dataset_resource_id' => 392,
                    'resource_id' => 1
                ]);
                NbdsTaxonExtension::create([
                    'cites' => (isset($item['CITES']) ? $item['CITES'] : null),
                    'morphological_description' => isset($item['mo_ta_dac_tinh_sinh_hoc']) ? $item['mo_ta_dac_tinh_sinh_hoc'] : null,
                    'use' => isset($item['gia_tri']) ? $item['gia_tri'] : null,
                    'habitat' => isset($item['moi_truong_song_hst']) ? $item['moi_truong_song_hst'] : null,
                    'nghi_dinh' => (isset($item['ND84'])) ? 84 : (isset($item['ND64']) ? 64 : null),
                    'phu_luc_nghi_dinh' => (isset($item['ND84'])) ? $item['ND84'] : (isset($item['ND64']) ? $item['ND64'] : null),
                    'phan_bo_ha_noi' => isset($item['nguon_goc_o_dia_phuong']) ? $item['nguon_goc_o_dia_phuong'] : null,
                    'red_list' => isset($item['sach_do_Viet_nam_27']) ? $item['sach_do_Viet_nam_27'] : null,
                    'iucn_2012' => isset($item['IUCN_212']) ? $item['IUCN_212'] : null,
                    'invasive_status' => isset($item['Xam_lan']) ? $item['Xam_lan'] : null,
                    'distribution_in_vietnam' => isset($item['nguon_goc_o_VN']) ? $item['nguon_goc_o_VN'] : null,
                    'darwin_core_taxon_id' => $darwinCoreTaxon['id']
                ]);
            }
            // $darwin_core_taxons = DarwinCoreTaxon::where('id', $item['STT']);
        }
        DB::commit();
        echo "done";
    }
}
