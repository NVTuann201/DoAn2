<?php

use App\DarwinCoreTaxon;
use App\ProtectedArea;
use App\Province;
use Illuminate\Database\Seeder;

class UpdateRegionProtectedArea extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
       
        $areas=ProtectedArea::get();

        foreach($areas as $area){
            $province=Province::where('name_vietnamese',$area->sub_loc)->first();
        
            if(!empty($province)){
                $area->region_id=$province->region_id;
                $area->save();
            }
        }

    }
}
