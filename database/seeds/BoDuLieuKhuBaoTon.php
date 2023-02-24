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

class BoDuLieuKhuBaoTon extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $myfile = fopen("database/seeds/dataKhuBaoTon.json", "r");
        $files = json_decode(fread($myfile, filesize("database/seeds/dataKhuBaoTon.json")));
        $data = $files->data;
        // dd($data);
        DB::beginTransaction();
        foreach ($data as $item) {
            $item = (array)$item;
            $species = Species::where('name', $item['ten_khoa_hoc'])->first();
            if ($species) {
                $genus = Genus::where('id', $species['genus_id'])->first();
                $family = Family::where('id', $genus['family_id'])->first();
                $order = Order::where('id', $family['order_id'])->first();
                $class = Classes::where('id', $order['class_id'])->first();
                $phylum = PhyLum::where('id', $class['phylum_id'])->first();
                $kingdom = KingDom::where('id', $phylum['kingdom_id'])->first();
                // $dataset_resource = DatasetResource::where('title', 'Dữ liệu Hà Nội 2015')->first();
                if (isset($item['vqg_ba_vi'])) {
                    DarwinCoreTaxon::create([
                        'scientific_name' => $item['ten_khoa_hoc'],
                        'kingdom_id' => $kingdom['id'],
                        'phylum_id' => $phylum['id'],
                        'class_id' => $class['id'],
                        'order_id' => $order['id'],
                        'family_id' => $family['id'],
                        'genus_id' => $genus['id'],
                        'species_id' => $species['id'],
                        'scientific_name_authorship' => isset($item['tac_gia']) ? $item['tac_gia'] : null,
                        'dataset_resource_id' => 395,
                        'resource_id' => 1
                    ]);
                }
                if (isset($item['K9'])) {
                    DarwinCoreTaxon::create([
                        'scientific_name' => $item['ten_khoa_hoc'],
                        'kingdom_id' => $kingdom['id'],
                        'phylum_id' => $phylum['id'],
                        'class_id' => $class['id'],
                        'order_id' => $order['id'],
                        'family_id' => $family['id'],
                        'genus_id' => $genus['id'],
                        'species_id' => $species['id'],
                        'scientific_name_authorship' => isset($item['tac_gia']) ? $item['tac_gia'] : null,
                        'dataset_resource_id' => 396,
                        'resource_id' => 1
                    ]);
                }if (isset($item['chua_thay'])) {
                    DarwinCoreTaxon::create([
                        'scientific_name' => $item['ten_khoa_hoc'],
                        'kingdom_id' => $kingdom['id'],
                        'phylum_id' => $phylum['id'],
                        'class_id' => $class['id'],
                        'order_id' => $order['id'],
                        'family_id' => $family['id'],
                        'genus_id' => $genus['id'],
                        'species_id' => $species['id'],
                        'scientific_name_authorship' => isset($item['tac_gia']) ? $item['tac_gia'] : null,
                        'dataset_resource_id' => 397,
                        'resource_id' => 1
                    ]);
                }if (isset($item['dong_mo'])) {
                    DarwinCoreTaxon::create([
                        'scientific_name' => $item['ten_khoa_hoc'],
                        'kingdom_id' => $kingdom['id'],
                        'phylum_id' => $phylum['id'],
                        'class_id' => $class['id'],
                        'order_id' => $order['id'],
                        'family_id' => $family['id'],
                        'genus_id' => $genus['id'],
                        'species_id' => $species['id'],
                        'scientific_name_authorship' => isset($item['tac_gia']) ? $item['tac_gia'] : null,
                        'dataset_resource_id' => 398,
                        'resource_id' => 1
                    ]);
                }if (isset($item['ho_suoi_hai'])) {
                    DarwinCoreTaxon::create([
                        'scientific_name' => $item['ten_khoa_hoc'],
                        'kingdom_id' => $kingdom['id'],
                        'phylum_id' => $phylum['id'],
                        'class_id' => $class['id'],
                        'order_id' => $order['id'],
                        'family_id' => $family['id'],
                        'genus_id' => $genus['id'],
                        'species_id' => $species['id'],
                        'scientific_name_authorship' => isset($item['tac_gia']) ? $item['tac_gia'] : null,
                        'dataset_resource_id' => 399,
                        'resource_id' => 1
                    ]);
                }if (isset($item['chua_huong'])) {
                    DarwinCoreTaxon::create([
                        'scientific_name' => $item['ten_khoa_hoc'],
                        'kingdom_id' => $kingdom['id'],
                        'phylum_id' => $phylum['id'],
                        'class_id' => $class['id'],
                        'order_id' => $order['id'],
                        'family_id' => $family['id'],
                        'genus_id' => $genus['id'],
                        'species_id' => $species['id'],
                        'scientific_name_authorship' => isset($item['tac_gia']) ? $item['tac_gia'] : null,
                        'dataset_resource_id' => 400,
                        'resource_id' => 1
                    ]);
                }if (isset($item['soc_son'])) {
                    DarwinCoreTaxon::create([
                        'scientific_name' => $item['ten_khoa_hoc'],
                        'kingdom_id' => $kingdom['id'],
                        'phylum_id' => $phylum['id'],
                        'class_id' => $class['id'],
                        'order_id' => $order['id'],
                        'family_id' => $family['id'],
                        'genus_id' => $genus['id'],
                        'species_id' => $species['id'],
                        'scientific_name_authorship' => isset($item['tac_gia']) ? $item['tac_gia'] : null,
                        'dataset_resource_id' => 401,
                        'resource_id' => 1
                    ]);
                }if (isset($item['vat_lai'])) {
                    DarwinCoreTaxon::create([
                        'scientific_name' => $item['ten_khoa_hoc'],
                        'kingdom_id' => $kingdom['id'],
                        'phylum_id' => $phylum['id'],
                        'class_id' => $class['id'],
                        'order_id' => $order['id'],
                        'family_id' => $family['id'],
                        'genus_id' => $genus['id'],
                        'species_id' => $species['id'],
                        'scientific_name_authorship' => isset($item['tac_gia']) ? $item['tac_gia'] : null,
                        'dataset_resource_id' => 402,
                        'resource_id' => 1
                    ]);
                }if (isset($item['nga_ba_song_da'])) {
                    DarwinCoreTaxon::create([
                        'scientific_name' => $item['ten_khoa_hoc'],
                        'kingdom_id' => $kingdom['id'],
                        'phylum_id' => $phylum['id'],
                        'class_id' => $class['id'],
                        'order_id' => $order['id'],
                        'family_id' => $family['id'],
                        'genus_id' => $genus['id'],
                        'species_id' => $species['id'],
                        'scientific_name_authorship' => isset($item['tac_gia']) ? $item['tac_gia'] : null,
                        'dataset_resource_id' => 403,
                        'resource_id' => 1
                    ]);
                }if (isset($item['den_va'])) {
                    DarwinCoreTaxon::create([
                        'scientific_name' => $item['ten_khoa_hoc'],
                        'kingdom_id' => $kingdom['id'],
                        'phylum_id' => $phylum['id'],
                        'class_id' => $class['id'],
                        'order_id' => $order['id'],
                        'family_id' => $family['id'],
                        'genus_id' => $genus['id'],
                        'species_id' => $species['id'],
                        'scientific_name_authorship' => isset($item['tac_gia']) ? $item['tac_gia'] : null,
                        'dataset_resource_id' => 404,
                        'resource_id' => 1
                    ]);
                }if (isset($item['nui_luot'])) {
                    DarwinCoreTaxon::create([
                        'scientific_name' => $item['ten_khoa_hoc'],
                        'kingdom_id' => $kingdom['id'],
                        'phylum_id' => $phylum['id'],
                        'class_id' => $class['id'],
                        'order_id' => $order['id'],
                        'family_id' => $family['id'],
                        'genus_id' => $genus['id'],
                        'species_id' => $species['id'],
                        'scientific_name_authorship' => isset($item['tac_gia']) ? $item['tac_gia'] : null,
                        'dataset_resource_id' => 405,
                        'resource_id' => 1
                    ]);
                }if (isset($item['vuon_bach_thao'])) {
                    DarwinCoreTaxon::create([
                        'scientific_name' => $item['ten_khoa_hoc'],
                        'kingdom_id' => $kingdom['id'],
                        'phylum_id' => $phylum['id'],
                        'class_id' => $class['id'],
                        'order_id' => $order['id'],
                        'family_id' => $family['id'],
                        'genus_id' => $genus['id'],
                        'species_id' => $species['id'],
                        'scientific_name_authorship' => isset($item['tac_gia']) ? $item['tac_gia'] : null,
                        'dataset_resource_id' => 406,
                        'resource_id' => 1
                    ]);
                }if (isset($item['vuon_thu'])) {
                    DarwinCoreTaxon::create([
                        'scientific_name' => $item['ten_khoa_hoc'],
                        'kingdom_id' => $kingdom['id'],
                        'phylum_id' => $phylum['id'],
                        'class_id' => $class['id'],
                        'order_id' => $order['id'],
                        'family_id' => $family['id'],
                        'genus_id' => $genus['id'],
                        'species_id' => $species['id'],
                        'scientific_name_authorship' => isset($item['tac_gia']) ? $item['tac_gia'] : null,
                        'dataset_resource_id' => 407,
                        'resource_id' => 1
                    ]);
                }if (isset($item['ho_tay'])) {
                    DarwinCoreTaxon::create([
                        'scientific_name' => $item['ten_khoa_hoc'],
                        'kingdom_id' => $kingdom['id'],
                        'phylum_id' => $phylum['id'],
                        'class_id' => $class['id'],
                        'order_id' => $order['id'],
                        'family_id' => $family['id'],
                        'genus_id' => $genus['id'],
                        'species_id' => $species['id'],
                        'scientific_name_authorship' => isset($item['tac_gia']) ? $item['tac_gia'] : null,
                        'dataset_resource_id' => 408,
                        'resource_id' => 1
                    ]);
                }if (isset($item['ho_guom'])) {
                    DarwinCoreTaxon::create([
                        'scientific_name' => $item['ten_khoa_hoc'],
                        'kingdom_id' => $kingdom['id'],
                        'phylum_id' => $phylum['id'],
                        'class_id' => $class['id'], 
                        'order_id' => $order['id'],
                        'family_id' => $family['id'],
                        'genus_id' => $genus['id'],
                        'species_id' => $species['id'],
                        'scientific_name_authorship' => isset($item['tac_gia']) ? $item['tac_gia'] : null,
                        'dataset_resource_id' => 409,
                        'resource_id' => 1
                    ]);
                }
            }
            // $darwin_core_taxons = DarwinCoreTaxon::where('id', $item['STT']);
        }
        DB::commit();
        echo "done";
    }
}
