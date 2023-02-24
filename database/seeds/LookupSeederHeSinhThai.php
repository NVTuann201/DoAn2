<?php

use App\Lookup;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LookupSeederHeSinhThai extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [
            [
                'name' => 'Hệ sinh thái cỏ cây tráng bụi',
                'code' => 'hst_dat_co_cay_trang_bui',
                'group' => 'hesinhthai',
            ],
            [
                'name' => 'Hệ sinh thái nông nghiệp',
                'code' => 'hst_nong_nghiep',
                'group' => 'hesinhthai',
            ],
            [
                'name' => 'Hệ sinh thái dân cư đô thị',
                'code' => 'hst_dan_cu_do_thi',
                'group' => 'hesinhthai',
            ],
            [
                'name' => 'Hệ sinh thái dân cư nông thôn',
                'code' => 'hst_dan_cu_nong_thon',
                'group' => 'hesinhthai',
            ],
            [
                'name' => 'Hệ sinh thái đất ngập nước',
                'code' => 'hst_dat_ngap_nuoc',
                'group' => 'hesinhthai',
            ],
            [
                'name' => 'Hệ sinh thái rừng kín thường xanh độ cao trên 600m',
                'code' => 'hst_rung_kin_thuong_xanh_tren_600m',
                'group' => 'hesinhthai',
            ],
            [
                'name' => 'Hệ sinh thái rừng kín thường xanh độ cao dưới 600m',
                'code' => 'hst_rung_kin_thuong_xanh_duoi_600m',
                'group' => 'hesinhthai',
            ],
            [
                'name' => 'Hệ sinh thái hỗ giao rừng tre nứa xem cây gỗ',
                'code' => 'hst_go_giao_rung_tre_nua_xen_cay_go',
                'group' => 'hesinhthai',
            ],
            [
                'name' => 'Hệ sinh thái rừng trên núi đá vôi',
                'code' => 'hst_rung_tren_nui_da_voi',
                'group' => 'hesinhthai',
            ],
            [
                'name' => 'Hệ sinh thái rừng trồng',
                'code' => 'hst_rung_trong',
                'group' => 'hesinhthai',
            ],
        ];
        DB::beginTransaction();
        foreach ($data as $item) {
            $checkAvaliable = Lookup::where('name', $item['name'])->where('code', $item['code'])->where('group', $item['group'])->first();
            if ($checkAvaliable) {
                $checkAvaliable->update($item);
            }
            else if (Lookup::where('code', 'hst_dan_cu')->first() && $item['code'] == 'hst_dan_cu_do_thi') {
                Lookup::where('code', 'hst_dan_cu')->update($item);
            } 
            else if (Lookup::where('code', 'hst_rung_tren_can')->first()  && $item['name'] == 'hst_rung_trong') {
                Lookup::where('code', 'hst_rung_tren_can')->update($item);
            } 
            else {
                Lookup::create($item);
            }
        }
        DB::commit();
        echo 'Seeder Done';
        return;
    }
}
