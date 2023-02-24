<?php

use App\DatasetResource;
use Illuminate\Database\Seeder;
use Carbon\Carbon;
class UpdateStatusDataSetSeeder extends Seeder
{
    public function run()
    {
       
        $date=Carbon::create(2019, 1, 1, 0, 0, 0);
        
        DatasetResource::where('publication_date','>=',$date)->update([
            "status"=>"public",
        ]);
        DatasetResource::where('publication_date','<',$date)->update([
            "status"=>"draft",
        ]);
            
    }
}