<?php

use App\CoSoBaoTon;
use App\District;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CoSoBaoTonSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $myfile = fopen("database/seeds/CoSoBaoTonHaNoi.geojson", "r");
        $files = json_decode(fread($myfile, filesize("database/seeds/CoSoBaoTonHaNoi.geojson")));
        $data = $files->features;

        foreach ($data as $item) {

            $geo = null;
            if ($item->geometry) {
                $selcet =  DB::select("select ST_GeomFromGeoJSON('" . json_encode($item->geometry) . "')");
                $geo = $selcet[0]->st_geomfromgeojson;
            }
            $huyen = District::where('name_vietnamese', $item->properties->Huyen)->first();
            $coSo = CoSoBaoTon::where('ten', $item->properties->Ten)->first();
            if($coSo){
                CoSoBaoTon::where('ten', $item->properties->Ten)->update([
                    'dia_chi' => $item->properties->DiaChi,
                    'chuc_nang' => $item->properties->ChucNang,
                    'quan_huyen_id' => $huyen ? $huyen->id : null,
                    'co_so_vat_chat' => $item->properties->CoSoVatCha,
                    'dien_tich' => $item->properties->DienTich,
                    'geom' => $geo
                ]);
            }else {
                CoSoBaoTon::create([
                    'ten' => $item->properties->Ten,
                    'dia_chi' => $item->properties->DiaChi,
                    'chuc_nang' => $item->properties->ChucNang,
                    'quan_huyen_id' => $huyen ? $huyen->id : null,
                    'co_so_vat_chat' => $item->properties->CoSoVatCha,
                    'dien_tich' => $item->properties->DienTich,
                    'geom' => $geo
                ]);
            }

        }
        echo "Done";
        return 'Done';
    }
}
