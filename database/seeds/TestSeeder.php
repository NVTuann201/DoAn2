<?php

use App\GiayPhepDaDangSinhHoc;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TestSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $geometry = [
            "type"=> "Point",
            "coordinates"=> [
              108.094482421875,
              22.177231792821342
            ]
    
            ];
        GiayPhepDaDangSinhHoc::create(['geom'=> 
            DB::raw('ST_GeomFromGeoJSON(\'' . json_encode($geometry) . '\')')
        ]);
    }
}
